<?php

declare(strict_types=1);

namespace AlmaviaCX\Bundle\IbexaImportExport\Writer\Csv;

use AlmaviaCX\Bundle\IbexaImportExport\Writer\Stream\StreamWriterOptions;

class CsvWriterOptions extends StreamWriterOptions
{
    protected string $delimiter = ',';
    protected string $enclosure = '"';
    protected bool $utf8Encode = false;
    protected bool $prependHeaderRow = false;

    public function getDelimiter(): string
    {
        return $this->delimiter;
    }

    public function setDelimiter(string $delimiter): CsvWriterOptions
    {
        $this->delimiter = $delimiter;

        return $this;
    }

    public function getEnclosure(): string
    {
        return $this->enclosure;
    }

    public function setEnclosure(string $enclosure): CsvWriterOptions
    {
        $this->enclosure = $enclosure;

        return $this;
    }

    public function isUtf8Encode(): bool
    {
        return $this->utf8Encode;
    }

    public function setUtf8Encode(bool $utf8Encode): CsvWriterOptions
    {
        $this->utf8Encode = $utf8Encode;

        return $this;
    }

    public function isPrependHeaderRow(): bool
    {
        return $this->prependHeaderRow;
    }

    public function setPrependHeaderRow(bool $prependHeaderRow): CsvWriterOptions
    {
        $this->prependHeaderRow = $prependHeaderRow;

        return $this;
    }
}
