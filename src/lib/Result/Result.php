<?php

declare(strict_types=1);

namespace AlmaviaCX\Bundle\IbexaImportExport\Result;

use DateInterval;
use DateTimeImmutable;

class Result
{
    protected DateTimeImmutable $startTime;
    protected DateTimeImmutable $endTime;
    protected DateInterval $elapsed;
    protected int $errorCount = 0;
    protected int $successCount = 0;
    protected int $totalProcessedCount = 0;
    protected array $exceptions;
    protected array $writerResults;

    public function __construct(
        DateTimeImmutable $startTime,
        DateTimeImmutable $endTime,
        int $totalCount,
        array $exceptions,
        array $writerResults
    ) {
        $this->startTime = $startTime;
        $this->endTime = $endTime;
        $this->elapsed = $startTime->diff($endTime);
        $this->totalProcessedCount = $totalCount;
        $this->errorCount = count($exceptions);
        $this->successCount = $totalCount - $this->errorCount;
        $this->exceptions = $exceptions;
        $this->writerResults = $writerResults;
    }

    public function getStartTime(): DateTimeImmutable
    {
        return $this->startTime;
    }

    public function getEndTime(): DateTimeImmutable
    {
        return $this->endTime;
    }

    /**
     * @return \DateInterval|false
     */
    public function getElapsed()
    {
        return $this->elapsed;
    }

    public function getErrorCount(): ?int
    {
        return $this->errorCount;
    }

    public function getSuccessCount(): ?int
    {
        return $this->successCount;
    }

    public function getTotalProcessedCount(): int
    {
        return $this->totalProcessedCount;
    }

    public function getExceptions(): array
    {
        return $this->exceptions;
    }

    public function getWriterResults(): array
    {
        return $this->writerResults;
    }
}
