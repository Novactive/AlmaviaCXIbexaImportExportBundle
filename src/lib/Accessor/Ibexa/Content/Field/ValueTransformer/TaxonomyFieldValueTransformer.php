<?php

namespace AlmaviaCX\Bundle\IbexaImportExport\Accessor\Ibexa\Content\Field\ValueTransformer;

use Ibexa\Contracts\Core\Repository\Values\Content\Field;
use Ibexa\Contracts\Core\Repository\Values\ContentType\FieldDefinition;
use Ibexa\Taxonomy\FieldType\TaxonomyEntryAssignment\Value as TaxonomyEntryAssignmentValue;

class TaxonomyFieldValueTransformer implements ContentFieldValueTransformerInterface
{
    public function __invoke(Field $field, FieldDefinition $fieldDefinition): object
    {
        /** @var TaxonomyEntryAssignmentValue $fieldValue */
        $fieldValue = $field->value;
        $taxonomyEntries = $fieldValue->getTaxonomyEntries();
        return  (object) [
            'names' => implode(', ', array_map(static function ($entry)  {
                return $entry->name;
            }, $taxonomyEntries)),
            'ids' => implode(', ', array_map(static function ($entry)  {
                return $entry->id;
            }, $taxonomyEntries)),
        ];
    }
}