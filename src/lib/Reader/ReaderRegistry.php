<?php

declare(strict_types=1);

namespace AlmaviaCX\Bundle\IbexaImportExport\Reader;

class ReaderRegistry
{
    /**
     * @var \AlmaviaCX\Bundle\IbexaImportExport\Reader\ReaderInterface[]
     */
    protected array $readers = [];

    public function __construct(
        iterable $readers
    ) {
        foreach ($readers as $reader) {
            $this->registerReader($reader);
        }
    }

    public function getAvailableReaders(): array
    {
        return array_keys($this->readers);
    }

    public function registerReader(ReaderInterface $reader)
    {
        $this->readers[$reader->getIdentifier()] = $reader;
    }

    public function get(string $identifier): ReaderInterface
    {
        return $this->readers[$identifier];
    }
}
