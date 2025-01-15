<?php

declare(strict_types=1);

namespace AlmaviaCX\Bundle\IbexaImportExport\Writer\Ibexa\Taxonomy;

use Ibexa\Contracts\Core\Repository\Repository;
use Ibexa\Contracts\Taxonomy\Service\TaxonomyServiceInterface;
use Ibexa\Contracts\Taxonomy\Value\TaxonomyEntry;
use Ibexa\Taxonomy\Exception\TaxonomyEntryNotFoundException;

class IbexaTaxonomyImporter
{
    public function __construct(
        protected Repository $repository,
        protected TaxonomyServiceInterface $taxonomyService,
        protected IbexaTaxonomyCreator $taxonomyCreator,
        protected IbexaTaxonomyUpdater $taxonomyUpdater,
    ) {
    }

    /**
     * @param \AlmaviaCX\Bundle\IbexaImportExport\Writer\Ibexa\Taxonomy\IbexaTaxonomyData $ibexaTaxonomyData
     *
     * @throws \Ibexa\Contracts\Core\Repository\Exceptions\BadStateException
     * @throws \Ibexa\Contracts\Core\Repository\Exceptions\ContentFieldValidationException
     * @throws \Ibexa\Contracts\Core\Repository\Exceptions\ContentValidationException
     * @throws \Ibexa\Contracts\Core\Repository\Exceptions\InvalidArgumentException
     * @throws \Ibexa\Contracts\Core\Repository\Exceptions\NotFoundException
     * @throws \Ibexa\Contracts\Core\Repository\Exceptions\UnauthorizedException
     * @throws \Ibexa\Taxonomy\Exception\TaxonomyConfigurationNotFoundException
     * @throws \Ibexa\Taxonomy\Exception\TaxonomyEntryNotFoundException
     * @throws \Ibexa\Taxonomy\Exception\TaxonomyNotFoundException
     * @throws \Throwable
     *
     * @return array{action: ?string, taxonomyEntry: TaxonomyEntry}|null
     */
    public function __invoke(IbexaTaxonomyData $ibexaTaxonomyData): ?array
    {
        $remoteId = $ibexaTaxonomyData->getContentRemoteId();
        $ownerId = $ibexaTaxonomyData->getOwnerId();
        if (null === $ownerId) {
            $ownerId = $this->repository
                ->getPermissionResolver()
                ->getCurrentUserReference()
                ->getUserId();
        }

        try {
            $parent = $this->taxonomyService->loadEntryByIdentifier(
                $ibexaTaxonomyData->getParentIdentifier(),
                $ibexaTaxonomyData->getTaxonomyName()
            );
            try {
                $taxonomyEntry = $this->taxonomyService->loadEntryByIdentifier(
                    $ibexaTaxonomyData->getIdentifier(),
                    $ibexaTaxonomyData->getTaxonomyName()
                );
                if (IbexaTaxonomyData::IMPORT_MODE_CREATE_ONLY === $ibexaTaxonomyData->getImportMode()) {
                    return [
                        'action' => null,
                        'taxonomyEntry' => $taxonomyEntry,
                    ];
                }

                $taxonomyEntry = ($this->taxonomyUpdater)(
                    $taxonomyEntry,
                    $parent,
                    $ibexaTaxonomyData->getNames(),
                    $ownerId,
                    $ibexaTaxonomyData->getMainLanguageCode()
                );

                return [
                    'action' => 'update',
                    'taxonomyEntry' => $taxonomyEntry,
                ];
            } catch (TaxonomyEntryNotFoundException $exception) {
                if (IbexaTaxonomyData::IMPORT_MODE_ONLY_UPDATE === $ibexaTaxonomyData->getImportMode()) {
                    return null;
                }

                $taxonomyEntry = ($this->taxonomyCreator)(
                    $ibexaTaxonomyData->getIdentifier(),
                    $parent,
                    $ibexaTaxonomyData->getNames(),
                    $remoteId,
                    $ownerId,
                    $ibexaTaxonomyData->getMainLanguageCode(),
                    $ibexaTaxonomyData->getSectionId(),
                    $ibexaTaxonomyData->getModificationDate()
                );

                return [
                    'action' => 'create',
                    'taxonomyEntry' => $taxonomyEntry,
                ];
            }
        } catch (\Throwable $exception) {
            dump($exception, $ibexaTaxonomyData);
            throw $exception;
        }
    }
}
