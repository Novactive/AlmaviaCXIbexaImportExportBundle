<?php

declare(strict_types=1);

namespace AlmaviaCX\Bundle\IbexaImportExport\Ibexa\Content\Field;

use Symfony\Component\VarExporter\LazyGhostTrait;

class ContentFieldAccessor
{
    use LazyGhostTrait;

    public $value;
}
