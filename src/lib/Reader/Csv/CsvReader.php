<?php

declare(strict_types=1);

namespace AlmaviaCX\Bundle\IbexaImportExport\Reader\Csv;

use AlmaviaCX\Bundle\IbexaImportExport\Reader\AbstractReader;
use Iterator;
use Symfony\Component\Translation\TranslatableMessage;

class CsvReader extends AbstractReader
{
    public function __invoke(): Iterator
    {
    }

    public function getIdentifier(): string
    {
        return 'reader.csv';
    }

    public static function getName()
    {
        return new TranslatableMessage(/* @Desc("CSV Reader") */ 'reader.csv.name');
    }

    public static function getOptionsFormType(): ?string
    {
        return CsvReaderOptionsFormType::class;
    }

    public static function getOptionsType(): ?string
    {
        return CsvReaderOptions::class;
    }
}
