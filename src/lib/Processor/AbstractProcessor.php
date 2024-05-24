<?php

declare(strict_types=1);

namespace AlmaviaCX\Bundle\IbexaImportExport\Processor;

use AlmaviaCX\Bundle\IbexaImportExport\Component\AbstractComponent;

abstract class AbstractProcessor extends AbstractComponent implements ProcessorInterface
{
    public function __invoke($item)
    {
        $processResult = $this->processItem($item);

        return null !== $processResult ? $processResult : $item;
    }

    abstract public function processItem($item);
}
