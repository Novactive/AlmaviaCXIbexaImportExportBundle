<?php

declare(strict_types=1);

namespace AlmaviaCX\Bundle\IbexaImportExport\Workflow;

use AlmaviaCX\Bundle\IbexaImportExport\Component\ComponentInterface;
use AlmaviaCX\Bundle\IbexaImportExport\Processor\ProcessorAggregator;
use AlmaviaCX\Bundle\IbexaImportExport\Reader\ReaderInterface;
use AlmaviaCX\Bundle\IbexaImportExport\Writer\WriterInterface;

class WorkflowRunConfiguration
{
    protected ReaderInterface $reader;
    protected array $processors;

    public function __construct(ReaderInterface $reader)
    {
        $this->reader = $reader;
    }

    public function getReader(): ReaderInterface
    {
        return $this->reader;
    }

    public function addProcessor(ComponentInterface $processor)
    {
        $this->processors[] = $processor;
    }

    /**
     * @return \AlmaviaCX\Bundle\IbexaImportExport\Writer\WriterInterface[]
     */
    public function getWriters(): array
    {
        return iterator_to_array($this->findWriters($this->processors));
    }

    protected function findWriters(array $processors): \Generator
    {
        foreach ($processors as $processor) {
            if ($processor instanceof WriterInterface) {
                yield $processor;
            }
            if ($processor instanceof ProcessorAggregator) {
                $this->findWriters($processor->getProcessors());
            }
        }
    }

    public function getProcessors(): array
    {
        return $this->processors;
    }
}
