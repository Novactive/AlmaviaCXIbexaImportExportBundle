<?php

declare(strict_types=1);

namespace AlmaviaCX\Bundle\IbexaImportExport\Accessor\Ibexa\Content;

use AlmaviaCX\Bundle\IbexaImportExport\Accessor\DatetimeAccessor;
use AlmaviaCX\Bundle\IbexaImportExport\Accessor\Ibexa\Content\Field\ContentFieldAccessorBuilder;
use Ibexa\Contracts\Core\Repository\Values\Content\Content;

class ContentAccessorBuilder
{
    protected ContentFieldAccessorBuilder $contentFieldAccessorBuilder;

    public function __construct(ContentFieldAccessorBuilder $contentFieldAccessorBuilder)
    {
        $this->contentFieldAccessorBuilder = $contentFieldAccessorBuilder;
    }

    public function build(Content $content): ContentAccessor
    {
        $initializers = [
            'fields' => function (ContentAccessor $instance) use ($content) {
                $fields = [];
                foreach ($content->getFields() as $field) {
                    $fieldDefinition = $content->getContentType()->getFieldDefinition($field->fieldDefIdentifier);
                    $fields[$field->fieldDefIdentifier] = $this->contentFieldAccessorBuilder->build($field, $fieldDefinition);
                }

                return $fields;
            },
            'name' => function (ContentAccessor $instance) use ($content) {
                return $content->getName();
            },
            'creationDate' => function (ContentAccessor $instance) use ($content) {
                return new DatetimeAccessor($content->versionInfo->creationDate);
            },
        ];

        return ContentAccessor::createLazyGhost($initializers);
    }
}
