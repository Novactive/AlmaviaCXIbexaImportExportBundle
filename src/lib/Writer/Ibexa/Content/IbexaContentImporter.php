<?php

declare(strict_types=1);

namespace AlmaviaCX\Bundle\IbexaImportExport\Writer\Ibexa\Content;

use Ibexa\Contracts\Core\Repository\Exceptions\NotFoundException;
use Ibexa\Contracts\Core\Repository\Repository;
use Ibexa\Contracts\Core\Repository\Values\Content\Content;

class IbexaContentImporter
{
    public function __construct(
        protected Repository $repository,
        protected IbexaContentUpdater $contentUpdater,
        protected IbexaContentCreator $contentCreator
    ) {
    }

    /**
     * @param \AlmaviaCX\Bundle\IbexaImportExport\Writer\Ibexa\Content\IbexaContentData $contentData
     *
     * @throws \Ibexa\Contracts\Core\Repository\Exceptions\BadStateException
     * @throws \Ibexa\Contracts\Core\Repository\Exceptions\ContentFieldValidationException
     * @throws \Ibexa\Contracts\Core\Repository\Exceptions\ContentValidationException
     * @throws \Ibexa\Contracts\Core\Repository\Exceptions\InvalidArgumentException
     * @throws \Ibexa\Contracts\Core\Repository\Exceptions\NotFoundException
     * @throws \Ibexa\Contracts\Core\Repository\Exceptions\UnauthorizedException
     */
    public function __invoke(IbexaContentData $contentData, bool $allowUpdate = true): Content
    {
        $remoteId = $contentData->getContentRemoteId();
        $ownerId = $contentData->getOwnerId();
        if (null === $ownerId) {
            $ownerId = $this->repository
                ->getPermissionResolver()
                ->getCurrentUserReference()
                ->getUserId();
        }

        try {
            try {
                $content = $this->repository->getContentService()->loadContentByRemoteId(
                    $contentData->getContentRemoteId()
                );
                if (!$allowUpdate) {
                    return $content;
                }

                return ($this->contentUpdater)(
                    $content,
                    $contentData->getFields(),
                    $contentData->getParentLocationIdList(),
                    $ownerId,
                    $contentData->getMainLanguageCode(),
                    $contentData->isHidden()
                );
            } catch (NotFoundException $exception) {
                return ($this->contentCreator)(
                    $contentData->getContentTypeIdentifier(),
                    $contentData->getParentLocationIdList(),
                    $contentData->getFields(),
                    $remoteId,
                    $ownerId,
                    $contentData->getMainLanguageCode(),
                    $contentData->getSectionId(),
                    $contentData->getModificationDate(),
                    $contentData->isHidden()
                );
            }
        } catch (\Throwable $exception) {
            dump($exception, $contentData);
            throw $exception;
        }
    }
}
