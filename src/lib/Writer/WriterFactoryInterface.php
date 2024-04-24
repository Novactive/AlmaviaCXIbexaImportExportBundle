<?php
declare( strict_types=1 );

namespace AlmaviaCX\Bundle\IbexaImportExport\Writer;

use Port\Writer;

interface WriterFactoryInterface
{
    public function __invoke(array $options): Writer;
}
