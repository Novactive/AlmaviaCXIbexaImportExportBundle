<?php

declare(strict_types=1);

namespace AlmaviaCX\Bundle\IbexaImportExport\Accessor\Ibexa\Content\Field\ValueTransformer;

use AlmaviaCX\Bundle\IbexaImportExport\Accessor\DatetimeAccessor;
use Ibexa\Contracts\Core\Repository\Values\Content\Field;

class DateFieldValueTransformer implements ContentFieldValueTransformerInterface
{

    public function __invoke(Field $field)
    {
        return new DatetimeAccessor($field->getValue()->date);
    }
}
