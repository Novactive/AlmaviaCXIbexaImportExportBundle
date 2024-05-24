<?php

declare(strict_types=1);

namespace AlmaviaCX\Bundle\IbexaImportExportBundle\DependencyInjection\CompilerPass;

use AlmaviaCX\Bundle\IbexaImportExport\Workflow\WorkflowRegistry;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\Compiler\ServiceLocatorTagPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

class WorkflowPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container): void
    {
        $servicesMap = [];
        foreach (array_keys($container->findTaggedServiceIds('almaviacx.importexport.workflow')) as $serviceId) {
            $serviceDefinition = $container->getDefinition($serviceId);
            $serviceDefinition->setShared(false);
            $servicesMap[$serviceDefinition->getClass()] = new Reference($serviceId);
        }

        $serviceLocator = ServiceLocatorTagPass::register($container, $servicesMap);

        $registryDefinition = $container->getDefinition(WorkflowRegistry::class);
        $registryDefinition->replaceArgument('$typeContainer', $serviceLocator);
        $registryDefinition->replaceArgument('$typesList', array_keys($servicesMap));
    }
}
