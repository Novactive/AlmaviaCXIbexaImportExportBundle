<?php

declare(strict_types=1);

namespace AlmaviaCX\Bundle\IbexaImportExport\Reader\Ibexa\ContentList;

use AlmaviaCX\Bundle\IbexaImportExport\Accessor\Ibexa\ValueAccessorBuilder;
use AlmaviaCX\Bundle\IbexaImportExport\Reader\AbstractReader;
use AlmaviaCX\Bundle\IbexaImportExport\Reader\Ibexa\ItemTransformer\ContentSearchHitTransformer;
use AlmaviaCX\Bundle\IbexaImportExport\Reader\ItemTransformer\ItemTransformerIterator;
use Ibexa\Contracts\Core\Repository\Iterator\BatchIterator;
use Ibexa\Contracts\Core\Repository\Iterator\BatchIteratorAdapter\ContentSearchAdapter;
use Ibexa\Contracts\Core\Repository\SearchService;
use Ibexa\Contracts\Core\Repository\Values\Content\Query;
use Iterator;
use Symfony\Component\Translation\TranslatableMessage;

class IbexaContentListReader extends AbstractReader
{
    protected ValueAccessorBuilder $valueAccessorBuilder;
    protected SearchService $searchService;

    public function __construct(ValueAccessorBuilder $valueAccessorBuilder, SearchService $searchService)
    {
        $this->valueAccessorBuilder = $valueAccessorBuilder;
        $this->searchService = $searchService;
    }

    public function __invoke(): Iterator
    {
        /** @var IbexaContentListReaderOptions $options */
        $options = $this->getOptions();

        $query = new Query();
        $query->filter = new Query\Criterion\LogicalAnd([
                                                             new Query\Criterion\ParentLocationId(
                                                                 $options->getParentLocationId()
                                                             ),
                                                         ]);

        return new ItemTransformerIterator(
            new BatchIterator(
                new ContentSearchAdapter($this->searchService, $query)
            ),
            new ContentSearchHitTransformer(
                $this->valueAccessorBuilder,
                $options->getMap()
            )
        );
    }

    public function getIdentifier(): string
    {
        return 'reader.ibexa.content_list';
    }

    public static function getName()
    {
        return new TranslatableMessage(/* @Desc("Content list") */ 'reader.ibexa.content_list.name');
    }

    public static function getOptionsFormType(): ?string
    {
        return IbexaContentListReaderOptionsFormType::class;
    }

    public static function getOptionsType(): ?string
    {
        return IbexaContentListReaderOptions::class;
    }
}
