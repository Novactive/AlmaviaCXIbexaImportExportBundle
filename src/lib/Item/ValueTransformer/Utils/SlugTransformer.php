<?php

declare(strict_types=1);

namespace AlmaviaCX\Bundle\IbexaImportExport\Item\ValueTransformer\Utils;

use AlmaviaCX\Bundle\IbexaImportExport\Item\ValueTransformer\AbstractItemValueTransformer;
use Ibexa\Core\Persistence\Legacy\Content\UrlAlias\SlugConverter;

/**
 * Transforms a string to its slug representation.
 */
class SlugTransformer extends AbstractItemValueTransformer
{
    public function __construct(
        protected SlugConverter $slugConverter
    ) {
    }

    /**
     * @param string $value
     */
    protected function transform(mixed $value, array $options = []): string
    {
        return $this->slugConverter->convert($value);
    }
}
