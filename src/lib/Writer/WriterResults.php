<?php

declare(strict_types=1);

namespace AlmaviaCX\Bundle\IbexaImportExport\Writer;

class WriterResults
{
    protected string $type;
    protected array $results;

    public function __construct(string $type, array $results)
    {
        $this->type = $type;
        $this->results = $results;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function getResults(): array
    {
        return $this->results;
    }
}
