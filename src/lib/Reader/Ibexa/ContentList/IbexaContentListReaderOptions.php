<?php

declare(strict_types=1);

namespace AlmaviaCX\Bundle\IbexaImportExport\Reader\Ibexa\ContentList;

use AlmaviaCX\Bundle\IbexaImportExport\Reader\ReaderOptions;

class IbexaContentListReaderOptions extends ReaderOptions
{
    protected int $parentLocationId;
    protected array $map;

    public function getParentLocationId(): int
    {
        return $this->parentLocationId;
    }

    public function setParentLocationId(int $parentLocationId): IbexaContentListReaderOptions
    {
        $this->parentLocationId = $parentLocationId;

        return $this;
    }

    public function getMap(): array
    {
        return $this->map;
    }

    public function setMap(array $map): IbexaContentListReaderOptions
    {
        $this->map = $map;

        return $this;
    }
}
