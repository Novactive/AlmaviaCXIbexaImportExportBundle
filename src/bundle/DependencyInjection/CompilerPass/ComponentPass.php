<?php

declare(strict_types=1);

namespace AlmaviaCX\Bundle\IbexaImportExportBundle\DependencyInjection\CompilerPass;

use AlmaviaCX\Bundle\IbexaImportExport\Component\ComponentRegistry;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\Compiler\ServiceLocatorTagPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

class ComponentPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container): void
    {
        $servicesMap = [];

        $serviceTags = [
            'almaviacx.importexport.reader',
            'almaviacx.importexport.step',
            'almaviacx.importexport.writer',
        ];

        foreach ($serviceTags as $serviceTag) {
            foreach (array_keys($container->findTaggedServiceIds($serviceTag)) as $serviceId) {
                $serviceDefinition = $container->getDefinition($serviceId);
                $serviceDefinition->setShared(false);
                $servicesMap[$serviceDefinition->getClass()] = new Reference($serviceId);
            }
        }

        $serviceLocator = ServiceLocatorTagPass::register($container, $servicesMap);

        $registryDefinition = $container->getDefinition(ComponentRegistry::class);
        $registryDefinition->replaceArgument('$typeContainer', $serviceLocator);
    }
}
