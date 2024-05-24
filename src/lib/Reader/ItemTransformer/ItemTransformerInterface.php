<?php

declare(strict_types=1);

namespace AlmaviaCX\Bundle\IbexaImportExport\Reader\ItemTransformer;

interface ItemTransformerInterface
{
    public function __invoke($item);
}
