<?php

declare(strict_types=1);

namespace AlmaviaCX\Bundle\IbexaImportExport\Ibexa\Content\Field\ValueTransformer;

use Ibexa\Contracts\Core\Repository\Values\Content\Field;
use Symfony\Component\PropertyAccess\PropertyAccess;

class GenericFieldValueTransformer implements ContentFieldValueTransformerInterface
{
    protected string $propertyName = 'value';

    public function __construct(
        string $propertyName = 'value'
    ) {
        $this->propertyName = $propertyName;
    }

    public function __invoke(Field $field)
    {
        $accessor = PropertyAccess::createPropertyAccessor();

        return $accessor->getValue($field, $this->propertyName);
    }
}
