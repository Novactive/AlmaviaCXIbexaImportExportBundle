<?php

declare(strict_types=1);

namespace AlmaviaCX\Bundle\IbexaImportExport\Accessor\Ibexa\Content;

use Symfony\Component\VarExporter\LazyGhostTrait;

class ContentAccessor
{
    use LazyGhostTrait;

    public array $fields;
    public string $name;
    public object $creationDate;
}
