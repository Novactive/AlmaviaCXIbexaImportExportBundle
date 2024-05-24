<?php

declare(strict_types=1);

namespace AlmaviaCX\Bundle\IbexaImportExport\Writer\Stream;

use AlmaviaCX\Bundle\IbexaImportExport\Writer\WriterOptions;

class StreamWriterOptions extends WriterOptions
{
    protected string $filepath;

    public function getFilepath(): string
    {
        return $this->filepath;
    }

    public function setFilepath(string $filepath): StreamWriterOptions
    {
        $this->filepath = $filepath;

        return $this;
    }
}
