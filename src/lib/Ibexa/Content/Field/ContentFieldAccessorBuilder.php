<?php

declare(strict_types=1);

namespace AlmaviaCX\Bundle\IbexaImportExport\Ibexa\Content\Field;

use AlmaviaCX\Bundle\IbexaImportExport\Ibexa\Content\Field\ValueTransformer\ContentFieldValueTransformerInterface;
use Ibexa\Contracts\Core\Repository\Values\Content\Field;

class ContentFieldAccessorBuilder
{
    /**
     * @var ContentFieldValueTransformerInterface[]
     */
    protected array $contentFieldValueTransformers;

    public function __construct(
        iterable $transformers
    ) {
        foreach ($transformers as $type => $transformer) {
            $this->contentFieldValueTransformers[$type] = $transformer;
        }
    }

    public function build(Field $field): ContentFieldAccessor
    {
        $initializers = [
            'value' => function (ContentFieldAccessor $instance) use ($field) {
                return $this->getValue($field);
            },
        ];

        return ContentFieldAccessor::createLazyGhost($initializers);
    }

    protected function getValue(Field $field)
    {
        $transformer = $this->contentFieldValueTransformers[$field->fieldTypeIdentifier] ?? null;
        if ($transformer) {
            return $transformer($field);
        }

        return (string) $field->value;
    }
}
