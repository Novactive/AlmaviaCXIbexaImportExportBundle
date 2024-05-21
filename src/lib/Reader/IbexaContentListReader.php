<?php

declare(strict_types=1);

namespace AlmaviaCX\Bundle\IbexaImportExport\Reader;

use Ibexa\Contracts\Core\Repository\Iterator\BatchIterator;
use Ibexa\Contracts\Core\Repository\Iterator\BatchIteratorAdapter\ContentSearchAdapter;
use Ibexa\Contracts\Core\Repository\SearchService;
use Ibexa\Contracts\Core\Repository\Values\Content\Query;
use Ibexa\Contracts\Core\Repository\Values\Content\Query\Criterion\LogicalAnd;
use Ibexa\Contracts\Core\Repository\Values\Content\Query\Criterion\ParentLocationId;
use Iterator;
use Port\Reader;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class IbexaContentListReader extends AbstractReader
{
    protected SearchService $searchService;

    public function __construct(SearchService $searchService)
    {
        $this->searchService = $searchService;
    }

    public function __invoke(array $options): Iterator
    {
        $query = new Query();
        $query->filter = new LogicalAnd([
                                             new ParentLocationId($options['parentLocationId']),
                                         ]);

        $iterator = new BatchIterator(new ContentSearchAdapter($this->searchService, $query));

        return new Reader\CountableIteratorReader($iterator);
    }

    protected function configureOptions(OptionsResolver $optionsResolver)
    {
        parent::configureOptions($optionsResolver);

        $optionsResolver->define('parentLocationId')
                        ->required();
    }

    public function getIdentifier(): string
    {
        return 'ibexa_content_list';
    }

    public function mapConfigurationForm(FormBuilderInterface $form): void
    {
        $form->add('parentLocationId', IntegerType::class);
    }

    public function mapJobForm(FormBuilderInterface $form): void
    {
        $form->add('parentLocationId', IntegerType::class);
    }
}
