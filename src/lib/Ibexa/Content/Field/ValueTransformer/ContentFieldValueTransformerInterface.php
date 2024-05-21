<?php

declare(strict_types=1);

namespace AlmaviaCX\Bundle\IbexaImportExport\Ibexa\Content\Field\ValueTransformer;

use Ibexa\Contracts\Core\Repository\Values\Content\Field;

interface ContentFieldValueTransformerInterface
{
    public function __invoke(Field $field);
}
