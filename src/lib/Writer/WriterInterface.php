<?php

declare(strict_types=1);

namespace AlmaviaCX\Bundle\IbexaImportExport\Writer;

use AlmaviaCX\Bundle\IbexaImportExport\Processor\ProcessorInterface;

interface WriterInterface extends ProcessorInterface
{
    public function prepare();

    public function finish(): WriterResults;
}
