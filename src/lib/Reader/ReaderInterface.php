<?php

declare(strict_types=1);

namespace AlmaviaCX\Bundle\IbexaImportExport\Reader;

use AlmaviaCX\Bundle\IbexaImportExport\Component\ComponentInterface;
use Iterator;

interface ReaderInterface extends ComponentInterface
{
    public function __invoke(): Iterator;
}
