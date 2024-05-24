<?php

declare(strict_types=1);

namespace AlmaviaCX\Bundle\IbexaImportExport\Writer\Csv;

use AlmaviaCX\Bundle\IbexaImportExport\Writer\Stream\AbstractStreamWriter;
use InvalidArgumentException;
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

        if (!is_array($item)) {
            throw new InvalidArgumentException('[CsvWriter] provided item must be an array.');
        }
        foreach ($item as $valueIdentifier => $value) {
            if (!is_scalar($value) && !is_null($value)) {
                throw new InvalidArgumentException(
                    sprintf(
                        '[CsvWriter] provided value for "%s" must be scalar instead of %s.',
                        $valueIdentifier,
                        gettype($value)
                    )
                );
            }
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
