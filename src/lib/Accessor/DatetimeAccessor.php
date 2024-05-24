<?php

declare(strict_types=1);

namespace AlmaviaCX\Bundle\IbexaImportExport\Accessor;

use DateTime;

class DatetimeAccessor
{
    public int $timestamp;
    public string $ISO8601;

    public function __construct(DateTime $dateTime)
    {
        $this->timestamp = $dateTime->getTimestamp();
        $this->ISO8601 = $dateTime->format('c');
    }
}