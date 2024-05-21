<?php

declare(strict_types=1);

namespace AlmaviaCX\Bundle\IbexaImportExportBundle\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\Config\Resource\FileResource;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Extension\PrependExtensionInterface;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\Yaml\Yaml;

class AlmaviaCXIbexaImportExportExtension extends Extension implements PrependExtensionInterface
{
    public function load(array $configs, ContainerBuilder $container)
    {
        $loader = new YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('default_settings.yml');
        $loader->load('services.yml');
        $loader->load('job.yaml');
        $loader->load('workflow.yaml');
        $loader->load('workflow_reader.yaml');
        $loader->load('workflow_step.yaml');
        $loader->load('workflow_writer.yaml');
        $loader->load('object_accessor.yaml');
        $loader->load('content_field_value_transformer.yaml');
    }

    public function prepend(ContainerBuilder $container)
    {
        $ibexaOrmConfig = [
            'orm' => [
                'entity_mappings' => [
                    'AlmaviaCXIbexaImportExport' => [
                        'type' => 'annotation',
                        'dir' => __DIR__.'/../../lib',
                        'prefix' => 'AlmaviaCX\Bundle\IbexaImportExport',
                        'is_bundle' => false,
                    ],
                ],
            ],
        ];
        $container->prependExtensionConfig('ibexa', $ibexaOrmConfig);

        $configs = [
            'ibexa.yaml' => 'ibexa',
        ];

        foreach ($configs as $fileName => $extensionName) {
            $configFile = __DIR__.'/../Resources/config/prepend/'.$fileName;
            $config = Yaml::parse(file_get_contents($configFile));
            $container->prependExtensionConfig($extensionName, $config);
            $container->addResource(new FileResource($configFile));
        }
    }
}
