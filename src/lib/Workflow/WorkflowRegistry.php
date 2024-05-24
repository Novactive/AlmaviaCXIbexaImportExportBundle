<?php

declare(strict_types=1);

namespace AlmaviaCX\Bundle\IbexaImportExport\Workflow;

use Psr\Container\ContainerInterface;
use ReflectionClass;

class WorkflowRegistry
{
    protected WorkflowConfigurationRepository $workflowConfigurationRepository;
    protected ConfigurableWorkflowFactory $configurableWorkflowFactory;
    protected ContainerInterface $typeContainer;
    protected array $typesList;

    public function __construct(
        ContainerInterface $typeContainer,
        array $typesList,
        WorkflowConfigurationRepository $workflowConfigurationRepository,
        ConfigurableWorkflowFactory $configurableWorkflowFactory
    ) {
        $this->typesList = $typesList;
        $this->typeContainer = $typeContainer;
        $this->configurableWorkflowFactory = $configurableWorkflowFactory;
        $this->workflowConfigurationRepository = $workflowConfigurationRepository;
    }

    public function getWorkflow(string $identifier): WorkflowInterface
    {
        return $this->typeContainer->get($identifier) ?? $this->buildFromWorkflowConfiguration($identifier);
    }

    public function getWorkflowConfigurationFormType(string $identifier): ?string
    {
        if (class_exists($identifier)) {
            $reflectionClass = new ReflectionClass($identifier);
            if ($reflectionClass->implementsInterface(WorkflowInterface::class)) {
                /* @var \AlmaviaCX\Bundle\IbexaImportExport\Workflow\WorkflowConfiguration $baseConfig */
                return $reflectionClass->getMethod('getConfigurationFormType')->invoke(null);
            }
        }
        $workflowConfiguration = $this->workflowConfigurationRepository->find($identifier);
        if ($workflowConfiguration) {
            return ConfigurableWorkflow::getConfigurationFormType();
        }

        return null;
    }

    public function getWorkflowDefaultConfiguration(string $identifier): ?WorkflowConfiguration
    {
        if (class_exists($identifier)) {
            $reflectionClass = new ReflectionClass($identifier);
            if ($reflectionClass->implementsInterface(WorkflowInterface::class)) {
                /* @var \AlmaviaCX\Bundle\IbexaImportExport\Workflow\WorkflowConfiguration $baseConfig */
                return $reflectionClass->getMethod('getDefaultConfig')->invoke(null);
            }
        }

        $workflowConfiguration = $this->workflowConfigurationRepository->find($identifier);
        if ($workflowConfiguration) {
            return $workflowConfiguration;
        }

        return null;
    }

    protected function buildFromWorkflowConfiguration(string $configurationIdentifier): ?ConfigurableWorkflow
    {
        $workflowConfiguration = $this->getWorkflowDefaultConfiguration($configurationIdentifier);
        if ($workflowConfiguration) {
            return $this->configurableWorkflowFactory->build($workflowConfiguration);
        }

        return null;
    }

    public function getAvailableWorkflows(): array
    {
        $worflows = $this->workflowConfigurationRepository->getAll();
        foreach ($this->typesList as $type) {
            $baseConfig = $this->getWorkflowDefaultConfiguration($type);
            if ($baseConfig) {
                $worflows[$type] = $baseConfig->getName();
            }
        }

        return $worflows;
    }
}
