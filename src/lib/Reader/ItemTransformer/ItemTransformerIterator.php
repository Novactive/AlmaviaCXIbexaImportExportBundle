<?php

declare(strict_types=1);

namespace AlmaviaCX\Bundle\IbexaImportExport\Reader\ItemTransformer;

use Iterator;

class ItemTransformerIterator implements Iterator
{
    protected Iterator $innerIterator;
    protected ItemTransformerInterface $itemTransformer;

    /**
     * @param \AlmaviaCX\Bundle\IbexaImportExport\Reader\ItemTransformer\ItemTransformerInterface $itemTransformer
     */
    public function __construct(Iterator $innerIterator, ItemTransformerInterface $itemTransformer)
    {
        $this->innerIterator = $innerIterator;
        $this->itemTransformer = $itemTransformer;
    }

    public function current()
    {
        return ($this->itemTransformer)($this->innerIterator->current());
    }

    public function next(): void
    {
        $this->innerIterator->next();
    }

    public function key(): int
    {
        return $this->innerIterator->key();
    }

    public function valid(): bool
    {
        return $this->innerIterator->valid();
    }

    public function rewind(): void
    {
        $this->innerIterator->rewind();
    }
}
