<?php

declare(strict_types=1);

namespace AlmaviaCX\Bundle\IbexaImportExport\Accessor\Ibexa\Content\Field\ValueTransformer;

use AlmaviaCX\Bundle\IbexaImportExport\Accessor\Ibexa\ValueAccessor;
use Ibexa\Contracts\Core\Repository\Values\Content\Field;
use Ibexa\Core\FieldType\Relation\Value as RelationValue;
use Ibexa\Core\FieldType\RelationList\Value as RelationListValue;
use Symfony\Component\PropertyAccess\PropertyAccess;
use Ibexa\Contracts\Core\Repository\ContentService;

class RelationFieldValueTransformer implements ContentFieldValueTransformerInterface
{
    public function __construct(
        protected ContentService $contentService
    )
    {
    }

    public function __invoke(Field $field)
    {
        $fieldValue = $field->getValue();
        if ($fieldValue instanceof RelationValue && $fieldValue->destinationContentId !== null) {
            $destinationContentIds = [$fieldValue->destinationContentId];
        }
        if ($fieldValue instanceof RelationListValue) {
            $destinationContentIds = $fieldValue->destinationContentIds;
        }

        $contentNames = array_map(function (int $destinationContentId) {
            return $this->contentService->loadContentInfo($destinationContentId)->name;
        }, $destinationContentIds);

        return implode(', ', $contentNames);
    }
}
