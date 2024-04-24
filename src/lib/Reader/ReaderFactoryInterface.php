<?php

declare( strict_types=1 );

namespace AlmaviaCX\Bundle\IbexaImportExport\Reader;

use Port\Reader;

interface ReaderFactoryInterface
{
    public function __invoke(array $options): Reader;
}
