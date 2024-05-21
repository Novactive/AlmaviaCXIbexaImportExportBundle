<?php

declare(strict_types=1);

namespace AlmaviaCX\Bundle\IbexaImportExport\Job;

use Doctrine\ORM\EntityManagerInterface;

class JobRepository
{
    protected EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function findById(int $id): ?Job
    {
        return $this->entityManager->getRepository(Job::class)->findOneBy(['id' => $id]);
    }

    public function save(Job $job): void
    {
        $this->entityManager->persist($job);
        $this->entityManager->flush();
    }

    public function count(): int
    {
        return $this->entityManager->getRepository(Job::class)->count([]);
    }

    public function find($limit = 10, $offset = 0): array
    {
        return $this->entityManager->getRepository(Job::class)->findBy(
            [],
            ['requestedDate' => 'ASC'],
            $limit,
            $offset
        );
    }
}
