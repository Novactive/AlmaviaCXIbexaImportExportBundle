<?php

declare(strict_types=1);

namespace AlmaviaCX\Bundle\IbexaImportExport\Workflow;

use AlmaviaCX\Bundle\IbexaImportExport\Reference\ReferenceBag;
use AlmaviaCX\Bundle\IbexaImportExport\Writer\WriterResults;
use DateTimeImmutable;
use Doctrine\Common\Collections\ArrayCollection;

class WorkflowState
{
    /**
     * @param array<string, WriterResults>   $writersResults
     * @param ArrayCollection<string, mixed> $cache
     */
    public function __construct(
        protected ?DateTimeImmutable $startTime = null,
        protected ?DateTimeImmutable $endTime = null,
        protected ?int $totalItemsCount = null,
        protected int $offset = 0,
        protected array $writersResults = [],
        protected ReferenceBag $referenceBag = new ReferenceBag(),
        protected ArrayCollection $cache = new ArrayCollection()
    ) {
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
