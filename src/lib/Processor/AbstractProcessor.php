<?php

declare(strict_types=1);

namespace AlmaviaCX\Bundle\IbexaImportExport\Processor;

use AlmaviaCX\Bundle\IbexaImportExport\Component\AbstractComponent;
use AlmaviaCX\Bundle\IbexaImportExport\Item\ItemAccessorInterface;

/**
 * @phpstan-import-type ProcessableItem from \AlmaviaCX\Bundle\IbexaImportExport\Processor\ProcessorInterface
 *
 * @template TProcessorOptions of ProcessorOptions
 * @extends  AbstractComponent<TProcessorOptions>
 * @implements ProcessorInterface<TProcessorOptions>
 */
abstract class AbstractProcessor extends AbstractComponent implements ProcessorInterface
{
    public function __invoke($item): mixed
    {
        $processResult = $this->processItem($item);

        return null !== $processResult ? $processResult : $item;
    }

    /**
     *  Return "false" to stop further processing of the item.
     *  Return "null" if the item has not been processed and should be passed to the next processor.
     *  Else, return the processed item.
     *
     * @param ProcessableItem $item
     *
     * @return ItemAccessorInterface|bool|null
     */
    abstract public function processItem($item): mixed;
}
