<?php

declare(strict_types=1);

namespace AlmaviaCX\Bundle\IbexaImportExport\Workflow;

use AlmaviaCX\Bundle\IbexaImportExport\Reader\ReaderInterface;
use AlmaviaCX\Bundle\IbexaImportExport\Reader\ReaderRegistry;
use AlmaviaCX\Bundle\IbexaImportExport\Step\StepRegistry;
use AlmaviaCX\Bundle\IbexaImportExport\Writer\WriterRegistry;

class ConfigurableWorkflowFactory
{
    protected WriterRegistry $writerRegistry;
    protected ReaderRegistry $readerRegistry;
    protected StepRegistry $stepRegistry;

    public function __construct(
        WriterRegistry $writerRegistry,
        ReaderRegistry $readerRegistry,
        StepRegistry $stepRegistry
    ) {
        $this->writerRegistry = $writerRegistry;
        $this->readerRegistry = $readerRegistry;
        $this->stepRegistry = $stepRegistry;
    }

    public function build(WorkflowConfiguration $workflowConfiguration): ConfigurableWorkflow
    {
        $configuration = $workflowConfiguration->getConfiguration();

        return new ConfigurableWorkflow(
            $workflowConfiguration,
            $this->getReader($configuration['reader']['identifier']),
            $this->getWriters(
                array_map(function (array $writerConfiguration) {
                    return $writerConfiguration['identifier'];
                }, $configuration['writers'])
            ),
            $this->getSteps(array_map(function (array $writerConfiguration) {
                return $writerConfiguration['identifier'];
            }, $configuration['steps'])),
        );
    }

    protected function getReader(string $identifier): ReaderInterface
    {
        return $this->readerRegistry->get($identifier);
    }

    /**
     * @return \AlmaviaCX\Bundle\IbexaImportExport\Writer\WriterInterface[]
     */
    protected function getWriters(array $identifiers): iterable
    {
        foreach ($identifiers as $identifier) {
            yield $this->writerRegistry->get($identifier);
        }
    }

    /**
     * @return \AlmaviaCX\Bundle\IbexaImportExport\Step\StepInterface[]
     */
    protected function getSteps(array $identifiers): iterable
    {
        foreach ($identifiers as $identifier) {
            yield $this->stepRegistry->get($identifier);
        }
    }
}
