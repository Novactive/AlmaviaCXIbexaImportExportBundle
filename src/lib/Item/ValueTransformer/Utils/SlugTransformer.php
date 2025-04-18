<?php

declare(strict_types=1);

namespace AlmaviaCX\Bundle\IbexaImportExport\Item\ValueTransformer\Utils;

use AlmaviaCX\Bundle\IbexaImportExport\Item\ValueTransformer\AbstractItemValueTransformer;
use Ibexa\Core\Persistence\Legacy\Content\UrlAlias\SlugConverter;

class SlugTransformer extends AbstractItemValueTransformer
{
    protected SlugConverter $slugConverter;

    public function __construct(SlugConverter $slugConverter)
    {
        $this->slugConverter = $slugConverter;
    }

    /**
     * @param string $value
     */
    public function transform($value, array $options = [])
    {
        if (is_array($value)) {
            $values = [];
            foreach ($value as $string) {
                $values[] = $this->slugConverter->convert($string);
            }

            return $values;
        }

        return $this->slugConverter->convert($value);
    }
}
