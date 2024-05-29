<?php

declare(strict_types=1);

namespace AlmaviaCX\Bundle\IbexaImportExport\Accessor\Ibexa\Content\Field\ValueTransformer;

use AlmaviaCX\Bundle\IbexaImportExport\Accessor\DatetimeAccessor;
use Ibexa\Contracts\Core\Repository\Values\Content\Field;

class DateTimeFieldValueTransformer implements ContentFieldValueTransformerInterface
{

    public function __invoke(Field $field)
    {
        $dateTimeAccessor = new DatetimeAccessor($field->getValue()->value);

        return $dateTimeAccessor->YMD;
    }
}
