<?php

declare(strict_types=1);

namespace AlmaviaCX\Bundle\IbexaImportExport\Job;

use DateTimeImmutable;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 * @ORM\Table(name="import_export_job")
 */
class Job
{
    public const STATUS_PENDING = 0;
    public const STATUS_RUNNING = 1;
    public const STATUS_COMPLETED = 2;
    public const STATUS_QUEUED = 3;

    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column
     */
    protected int $id;

    /**
     * @ORM\Column
     */
    private string $label;

    /**
     * @ORM\Column
     */
    protected string $workflowIdentifier;

    /**
     * @ORM\Column
     */
    protected int $status = self::STATUS_PENDING;

    /**
     * @ORM\Column(type="datetime_immutable")
     */
    private DateTimeImmutable $requestedDate;

    /**
     * @ORM\Column(type="datetime_immutable", nullable=true)
     */
    protected ?DateTimeImmutable $startTime = null;

    /**
     * @ORM\Column(type="datetime_immutable", nullable=true)
     */
    protected ?DateTimeImmutable $endTime = null;

    /**
     * @ORM\Column
     */
    protected int $creatorId;

    /**
     * @ORM\Column
     */
    protected array $options;

    /**
     * @ORM\Column
     */
    protected array $exceptions = [];

    /**
     * @ORM\Column(nullable=true)
     */
    protected ?string $writerResults = null;

    public function getId(): int
    {
        return $this->id;
    }

    public function getLabel(): string
    {
        return $this->label;
    }

    public function setLabel(string $label): void
    {
        $this->label = $label;
    }

    public function getWorkflowIdentifier(): string
    {
        return $this->workflowIdentifier;
    }

    public function setWorkflowIdentifier(string $workflowIdentifier): void
    {
        $this->workflowIdentifier = $workflowIdentifier;
    }

    public function getStatus(): int
    {
        return $this->status;
    }

    public function setStatus(int $status): void
    {
        $this->status = $status;
    }

    public function getRequestedDate(): DateTimeImmutable
    {
        return $this->requestedDate;
    }

    public function setRequestedDate(DateTimeImmutable $requestedDate): void
    {
        $this->requestedDate = $requestedDate;
    }

    public function getStartTime(): ?DateTimeImmutable
    {
        return $this->startTime;
    }

    public function setStartTime(?DateTimeImmutable $startTime): void
    {
        $this->startTime = $startTime;
    }

    public function getEndTime(): ?DateTimeImmutable
    {
        return $this->endTime;
    }

    public function setEndTime(?DateTimeImmutable $endTime): void
    {
        $this->endTime = $endTime;
    }

    public function getCreatorId(): int
    {
        return $this->creatorId;
    }

    public function setCreatorId(int $creatorId): void
    {
        $this->creatorId = $creatorId;
    }

    public function getOptions(): array
    {
        return $this->options;
    }

    public function setOptions(array $options): void
    {
        $this->options = $options;
    }

    public function getExceptions(): array
    {
        return $this->exceptions;
    }

    public function setExceptions(array $exceptions): void
    {
        $this->exceptions = $exceptions;
    }

    /**
     * @return \AlmaviaCX\Bundle\IbexaImportExport\Writer\WriterResults[]
     */
    public function getWriterResults(): array
    {
        return $this->writerResults ? unserialize($this->writerResults) : [];
    }

    public function setWriterResults(array $writerResults): void
    {
        $this->writerResults = serialize($writerResults);
    }
}
