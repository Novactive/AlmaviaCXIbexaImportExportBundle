<?php

namespace AlmaviaCX\Bundle\IbexaImportExport\Accessor\Ibexa\Content\Field\ValueTransformer;

use Ibexa\Contracts\Core\Repository\Values\Content\Field;
use Ibexa\Contracts\Core\Repository\Values\ContentType\FieldDefinition;

class SelectionFieldValueTransformer implements ContentFieldValueTransformerInterface
{
    public function __invoke(Field $field, FieldDefinition $fieldDefinition): string
    {
        $fieldValue = $field->getValue();
        $selectedValues = array_intersect_key($fieldDefinition->fieldSettings['options'], array_flip($fieldValue->selection));

        return implode(', ', $selectedValues);
    }
}