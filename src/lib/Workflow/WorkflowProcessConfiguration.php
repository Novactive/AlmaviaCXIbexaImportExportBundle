<?php

declare(strict_types=1);

namespace AlmaviaCX\Bundle\IbexaImportExport\Workflow;

use AlmaviaCX\Bundle\IbexaImportExport\Processor\ProcessorOptions;
use AlmaviaCX\Bundle\IbexaImportExport\Reader\ReaderOptions;

class WorkflowProcessConfiguration
{
    protected array $processors = [];

    protected array $reader = [];

    public function setReader(array $reader)
    {
        $this->reader = $reader;
    }

    public function setProcessors(array $processors): void
    {
        $this->processors = $processors;
    }

    public function addProcessor(array $processor)
    {
        $this->processors[] = $processor;
    }

    /**
     * @return array{type: string, options: ReaderOptions}
     */
    public function getReader(): array
    {
        return $this->reader;
    }

    /**
     * @return array<array{type: string, options: ProcessorOptions}>
     */
    public function getProcessors(): array
    {
        return $this->processors ?? [];
    }
}
