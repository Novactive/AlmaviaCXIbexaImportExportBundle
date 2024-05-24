<?php

declare(strict_types=1);

namespace AlmaviaCX\Bundle\IbexaImportExport\Writer\Csv;

use AlmaviaCX\Bundle\IbexaImportExport\Writer\Stream\AbstractStreamWriter;
use Symfony\Component\Translation\TranslatableMessage;

class CsvWriter extends AbstractStreamWriter
{
    private int $row = 1;

    public function prepare()
    {
        parent::prepare();
        $this->row = 1;
    }

    public function processItem($item)
    {
        /** @var CsvWriterOptions $options */
        $options = $this->getOptions();
        if ($options->isPrependHeaderRow() && 1 == $this->row++) {
            $headers = array_keys($item);
            fputcsv($this->stream, $headers, $options->getDelimiter(), $options->getEnclosure());
        }

        fputcsv($this->stream, $item, $options->getDelimiter(), $options->getEnclosure());
    }

    public function getIdentifier(): string
    {
        return 'writer.csv';
    }

    public static function getName()
    {
        return new TranslatableMessage(/* @Desc("CSV Writer") */ 'writer.csv.name');
    }

    public static function getOptionsType(): ?string
    {
        return CsvWriterOptions::class;
    }
}
