<?php

declare(strict_types=1);

namespace AlmaviaCX\Bundle\IbexaImportExport\Workflow;

use AlmaviaCX\Bundle\IbexaImportExport\Reference\ReferenceBag;
use AlmaviaCX\Bundle\IbexaImportExport\Writer\WriterResults;
use DateTimeImmutable;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="import_workflow_state")
 */
class WorkflowState
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue()
     * @ORM\Column
     */
    protected ?int $id = null;

    /**
     * @ORM\Column(nullable=true)
     */
    protected ?DateTimeImmutable $startTime = null;

    /**
     * @ORM\Column(nullable=true)
     */
    protected ?DateTimeImmutable $endTime = null;

    /**
     * @ORM\Column(nullable=true)
     */
    protected ?int $totalItemsCount = null;

    /**
     * @ORM\Column
     */
    protected int $offset = 0;

    /**
     * @ORM\Column(type="object")
     *
     * @var array<string, WriterResults>
     */
    protected array $writersResults = [];

    /**
     * @ORM\Column(type="object")
     */
    protected ReferenceBag $referenceBag;

    /**
     * @ORM\Column(type="object")
     *
     * @var ArrayCollection<string, mixed>
     */
    protected ArrayCollection $cache;

    /**
     * @param array<string, WriterResults>   $writersResults
     * @param ArrayCollection<string, mixed> $cache
     */
    public function __construct(
        ?DateTimeImmutable $startTime = null,
        ?DateTimeImmutable $endTime = null,
        ?int $totalItemsCount = null,
        int $offset = 0,
        array $writersResults = [],
        ReferenceBag $referenceBag = new ReferenceBag(),
        ArrayCollection $cache = new ArrayCollection()
    ) {
        $this->cache = $cache;
        $this->referenceBag = $referenceBag;
        $this->writersResults = $writersResults;
        $this->offset = $offset;
        $this->totalItemsCount = $totalItemsCount;
        $this->endTime = $endTime;
        $this->startTime = $startTime;
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

    public function getTotalItemsCount(): ?int
    {
        return $this->totalItemsCount;
    }

    public function setTotalItemsCount(?int $totalItemsCount): void
    {
        $this->totalItemsCount = $totalItemsCount;
    }

    public function getOffset(): int
    {
        return $this->offset;
    }

    public function setOffset(int $offset): void
    {
        $this->offset = $offset;
    }

    /**
     * @return array<string, WriterResults>
     */
    public function getWritersResults(): array
    {
        return $this->writersResults;
    }

    /**
     * @param array<string, WriterResults> $writersResults
     */
    public function setWritersResults(array $writersResults): void
    {
        $this->writersResults = $writersResults;
    }

    public function hasWriterResults(string $id): bool
    {
        return array_key_exists($id, $this->writersResults);
    }

    public function getWriterResults(string $id): ?WriterResults
    {
        return $this->writersResults[$id] ?? null;
    }

    public function setWriterResults(string $id, ?WriterResults $result): void
    {
        $this->writersResults[$id] = $result;
    }

    public function getReferenceBag(): ReferenceBag
    {
        return $this->referenceBag;
    }

    public function setReferenceBag(ReferenceBag $referenceBag): void
    {
        $this->referenceBag = $referenceBag;
    }

    public function getProgress(): int
    {
        return $this->totalItemsCount > 0 ? (int) round(($this->offset / $this->totalItemsCount * 100)) : 0;
    }

    public function isCompleted(): bool
    {
        return 100 === $this->getProgress();
    }

    /**
     * @return ArrayCollection<string, mixed>
     */
    public function getCache(): ArrayCollection
    {
        if (!isset($this->cache)) {
            $this->cache = new ArrayCollection();
        }

        return $this->cache;
    }

    public function getCacheItem(string $key, mixed $default = null): mixed
    {
        $cache = $this->getCache();
        if (!$cache->containsKey($key)) {
            $cache->set($key, $default);
        }

        return $cache->get($key);
    }
}
