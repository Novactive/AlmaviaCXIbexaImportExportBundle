<?php
declare( strict_types=1 );

namespace AlmaviaCX\Bundle\IbexaImportExport\Job;

class Job
{
    public const STATUS_PENDING = 0;
    public const STATUS_RUNNING = 1;
    public const STATUS_COMPLETED = 2;
    public const STATUS_QUEUED = 3;

    protected string $workflowIdentifier;

    protected int $status = self::STATUS_PENDING;

    protected ?\DateTimeInterface $startTime = null;

    protected ?\DateTimeInterface $endTime = null;

    protected array $options = [];

    /**
     * @return string
     */
    public function getWorkflowIdentifier(): string
    {
        return $this->workflowIdentifier;
    }

    public function getStatus(): int
    {
        return $this->status;
    }

    public function setStatus( int $status ): void
    {
        $this->status = $status;
    }

    public function getStartTime(): ?\DateTimeInterface
    {
        return $this->startTime;
    }

    public function setStartTime( ?\DateTimeInterface $startTime ): void
    {
        $this->startTime = $startTime;
    }

    public function getEndTime(): ?\DateTimeInterface
    {
        return $this->endTime;
    }

    public function setEndTime( ?\DateTimeInterface $endTime ): void
    {
        $this->endTime = $endTime;
    }

    /**
     * @return array
     */
    public function getOptions(): array
    {
        return $this->options;
    }
}
