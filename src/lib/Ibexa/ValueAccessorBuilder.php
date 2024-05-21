<?php

declare(strict_types=1);

namespace AlmaviaCX\Bundle\IbexaImportExport\Ibexa;

use AlmaviaCX\Bundle\IbexaImportExport\Ibexa\Content\ContentAccessorBuilder;
use Ibexa\Contracts\Core\Repository\ContentService;
use Ibexa\Contracts\Core\Repository\LocationService;
use Ibexa\Contracts\Core\Repository\Values\Content\Content;

class ValueAccessorBuilder
{
    protected LocationService $locationService;
    protected ContentService $contentService;
    protected ContentAccessorBuilder $contentAccessorBuilder;

    public function __construct(
        LocationService $locationService,
        ContentService $contentService,
        ContentAccessorBuilder $contentAccessorBuilder
    ) {
        $this->locationService = $locationService;
        $this->contentService = $contentService;
        $this->contentAccessorBuilder = $contentAccessorBuilder;
    }

    public function buildFromContent(Content $content): ValueAccessor
    {
        $initializers = [
            'content' => function (ValueAccessor $instance) use ($content) {
                return $this->contentAccessorBuilder->build($content);
            },
            'mainLocation' => function (ValueAccessor $instance) use ($content) {
                return $content->contentInfo->getMainLocation();
            },
            'contentType' => function (ValueAccessor $instance) use ($content) {
                return $content->contentInfo->getContentType();
            },
            'locations' => function (ValueAccessor $instance) use ($content) {
                return $this->locationService->loadLocations($content->contentInfo);
            },
        ];

        return $this->createLazyGhost($initializers);
    }

    protected function createLazyGhost(array $initializers): ValueAccessor
    {
        return ValueAccessor::createLazyGhost($initializers);
    }
}
