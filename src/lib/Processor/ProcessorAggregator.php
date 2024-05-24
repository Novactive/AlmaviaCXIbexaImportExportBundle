<?php

declare(strict_types=1);

namespace AlmaviaCX\Bundle\IbexaImportExport\Processor;

use AlmaviaCX\Bundle\IbexaImportExport\Component\AbstractComponent;
use Symfony\Component\Translation\TranslatableMessage;

class ProcessorAggregator extends AbstractComponent implements ProcessorInterface
{
    protected array $processors = [];

    public function __invoke($item)
    {
        foreach ($this->processors as $processor) {
            $item = ($processor)($item);
        }
    }

    public function addProcessor(ProcessorInterface $processor): void
    {
        $this->processors[] = $processor;
    }

    public function getProcessors(): array
    {
        return $this->processors;
    }

    public function getIdentifier(): string
    {
        return 'processor.aggregator';
    }

    public static function getName()
    {
        return new TranslatableMessage(/* @Desc("Aggregator") */ 'processor.aggregator.name');
    }
}
