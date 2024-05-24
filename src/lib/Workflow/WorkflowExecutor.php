<?php

declare(strict_types=1);

namespace AlmaviaCX\Bundle\IbexaImportExport\Workflow;

use AlmaviaCX\Bundle\IbexaImportExport\Component\ComponentRegistry;
use AlmaviaCX\Bundle\IbexaImportExport\Result\Result;

class WorkflowExecutor
{
    protected ComponentRegistry $componentRegistry;

    public function __construct(ComponentRegistry $componentRegistry)
    {
        $this->componentRegistry = $componentRegistry;
    }

    public function __invoke(WorkflowInterface $workflow, array $runtimeConfiguration): Result
    {
        $workflow->setConfiguration(
            $this->buildRunConfiguration(
                $workflow,
                $runtimeConfiguration
            )
        );

        return ($workflow)();
    }

    protected function buildRunConfiguration(
        WorkflowInterface $workflow,
        array $runtimeConfiguration
    ): WorkflowRunConfiguration {
        $baseConfiguration = $workflow::getDefaultConfig();

        $processConfiguration = $baseConfiguration->getProcessConfiguration();
        $readerConfiguration = $processConfiguration->getReader();

        $reader = $this->componentRegistry->getComponent($readerConfiguration['type']);
        $reader->setOptions(
            $readerConfiguration['options']->merge($runtimeConfiguration['reader'] ?? [])
        );

        $runConfiguration = new WorkflowRunConfiguration(
            $reader,
        );

        $processorsConfiguration = $processConfiguration->getProcessors();
        foreach ($processorsConfiguration as $index => $processorConfiguration) {
            $processor = $this->componentRegistry->getComponent($processorConfiguration['type']);
            $processor->setOptions(
                $processorConfiguration['options']->merge($runtimeConfiguration['processors'][$index] ?? [])
            );
            $runConfiguration->addProcessor($processor);
        }

        return $runConfiguration;
    }
}
