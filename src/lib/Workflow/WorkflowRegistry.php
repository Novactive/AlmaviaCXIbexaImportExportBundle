<?php

declare(strict_types=1);

namespace AlmaviaCX\Bundle\IbexaImportExport\Workflow;

class WorkflowRegistry
{
    /**
     * @var \AlmaviaCX\Bundle\IbexaImportExport\Workflow\WorkflowInterface[]
     */
    protected array $workflows = [];
    protected WorkflowConfigurationRepository $workflowConfigurationRepository;
    protected ConfigurableWorkflowFactory $configurableWorkflowFactory;

    public function __construct(
        iterable $workflows,
        WorkflowConfigurationRepository $workflowConfigurationRepository,
        ConfigurableWorkflowFactory $configurableWorkflowFactory
    ) {
        $this->configurableWorkflowFactory = $configurableWorkflowFactory;
        $this->workflowConfigurationRepository = $workflowConfigurationRepository;
        foreach ($workflows as $workflow) {
            $this->registerWorkflow($workflow);
        }
    }

    public function registerWorkflow(WorkflowInterface $workflow)
    {
        $this->workflows[$workflow->getIdentifier()] = $workflow;
    }

    public function get(string $identifier): WorkflowInterface
    {
        return $this->workflows[$identifier] ?? $this->buildFromWorkflowConfiguration($identifier);
    }

    protected function buildFromWorkflowConfiguration(string $configurationIdentifier): ?ConfigurableWorkflow
    {
        $workflowConfiguration = $this->workflowConfigurationRepository->find($configurationIdentifier);
        if (!$workflowConfiguration) {
            return null;
        }

        return $this->configurableWorkflowFactory->build($workflowConfiguration);
    }

    public function getAvailableWorkflows(): array
    {
        $worflows = $this->workflowConfigurationRepository->getAll();
        foreach ($this->workflows as $workflow) {
            $worflows[$workflow->getIdentifier()] = $workflow->getName();
        }

        return $worflows;
    }
}
