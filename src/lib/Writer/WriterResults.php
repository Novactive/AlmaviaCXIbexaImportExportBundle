<?php

declare(strict_types=1);

namespace AlmaviaCX\Bundle\IbexaImportExport\Writer;

class WriterResults extends \ArrayObject
{
    public function __construct(string $writerIdentifier, $array = [], $flags = 0, $iteratorClass = 'ArrayIterator')
    {
        $array['writerIdentifier'] = $writerIdentifier;
        parent::__construct($array, $flags, $iteratorClass);
    }
}
