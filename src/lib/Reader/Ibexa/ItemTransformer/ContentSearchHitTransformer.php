<?php

declare(strict_types=1);

namespace AlmaviaCX\Bundle\IbexaImportExport\Reader\Ibexa\ItemTransformer;

use AlmaviaCX\Bundle\IbexaImportExport\Accessor\Ibexa\ValueAccessorBuilder;
use AlmaviaCX\Bundle\IbexaImportExport\Reader\ItemTransformer\ItemTransformerInterface;
use Ibexa\Contracts\Core\Repository\Values\Content\Content;
use Ibexa\Contracts\Core\Repository\Values\Content\Search\SearchHit;
use Symfony\Component\PropertyAccess\PropertyAccess;

class ContentSearchHitTransformer implements ItemTransformerInterface
{
    protected ValueAccessorBuilder $valueAccessorBuilder;
    protected array $map;

    public function __construct(ValueAccessorBuilder $valueAccessorBuilder, array $map)
    {
        $this->valueAccessorBuilder = $valueAccessorBuilder;
        $this->map = $map;
    }

    public function __invoke($item)
    {
        if ($item instanceof SearchHit && $item->valueObject instanceof Content) {
            return $this->getPropertiesValuesFromMap($item->valueObject, $this->map);
        }

        return null;
    }

    protected function getPropertiesValuesFromMap(Content $content, array $map): array
    {
        $propertyAccessor = PropertyAccess::createPropertyAccessor();
        $contentValueAccessor = $this->valueAccessorBuilder->buildFromContent($content);

        $values = [];
        foreach ($map as $destination => $source) {
            $value = $contentValueAccessor->getValue($source);
            $propertyAccessor->setValue($values, $destination, $value);
        }

        return $values;
    }
}
