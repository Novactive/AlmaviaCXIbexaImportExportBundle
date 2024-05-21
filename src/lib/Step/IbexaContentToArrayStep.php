<?php

declare(strict_types=1);

namespace AlmaviaCX\Bundle\IbexaImportExport\Step;

use AlmaviaCX\Bundle\IbexaImportExport\Ibexa\ValueAccessorBuilder;
use Ibexa\Contracts\Core\Repository\Values\Content\Content;
use Ibexa\Contracts\Core\Repository\Values\Content\Search\SearchHit;
use Symfony\Component\OptionsResolver\OptionsResolver;

class IbexaContentToArrayStep extends AbstractStep
{
    protected ValueAccessorBuilder $valueAccessorBuilder;

    public function __construct(ValueAccessorBuilder $valueAccessorBuilder)
    {
        $this->valueAccessorBuilder = $valueAccessorBuilder;
    }

    public function getIdentifier(): string
    {
        return 'ibexa_content_to_array_step';
    }

    public function __invoke($item, array $options)
    {
        if ($item instanceof SearchHit && $item->valueObject instanceof Content) {
            $options = $this->resolveOptions($options);

            return $this->getPropertiesValuesFromMap($item->valueObject, $options['map']);
        }

        return false;
    }

    protected function getPropertiesValuesFromMap(Content $content, array $map): array
    {
        $propertyAccessor = $this->valueAccessorBuilder->buildFromContent($content);

        $values = [];
        foreach ($map as $destination => $source) {
            $values[$destination] = $propertyAccessor->getValue($source);
        }

        return $values;
    }

    protected function configureOptions(OptionsResolver $optionsResolver)
    {
        parent::configureOptions($optionsResolver);
        $optionsResolver->define('map')->default([])->allowedTypes('string[]');
    }
}
