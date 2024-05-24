<?php

declare(strict_types=1);

namespace AlmaviaCX\Bundle\IbexaImportExport\Writer;

use AlmaviaCX\Bundle\IbexaImportExport\Processor\AbstractProcessor;

abstract class AbstractWriter extends AbstractProcessor implements WriterInterface
{
    public static function getOptionsType(): ?string
    {
        return WriterOptions::class;
    }

    public function finish(): WriterResults
    {
        return new WriterResults(static::class, []);
    }
}
