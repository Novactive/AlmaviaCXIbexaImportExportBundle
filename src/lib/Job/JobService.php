<?php

declare(strict_types=1);

namespace AlmaviaCX\Bundle\IbexaImportExport\Job;

use DateTimeImmutable;

class JobService
{
    protected JobRepository $jobRepository;
    protected JobRunner $jobRunner;

    /**
     * @param \AlmaviaCX\Bundle\IbexaImportExport\Job\JobRepository $jobRepository
     * @param \AlmaviaCX\Bundle\IbexaImportExport\Job\JobRunner     $jobRunner
     */
    public function __construct(JobRepository $jobRepository, JobRunner $jobRunner)
    {
        $this->jobRepository = $jobRepository;
        $this->jobRunner = $jobRunner;
    }

    public function createJob(Job $job)
    {
        $job->setRequestedDate(new DateTimeImmutable());
        $job->setStatus(Job::STATUS_PENDING);

        $this->jobRepository->save($job);

        $this->runJob($job);
    }

    public function runJob(Job $job)
    {
        $job->setStatus(Job::STATUS_RUNNING);
        $job->setStartTime(new DateTimeImmutable());
        $this->jobRepository->save($job);

        $results = ($this->jobRunner)($job);

        $job->setStatus(Job::STATUS_COMPLETED);
        $job->setEndTime($results->getEndTime());
        $job->setWriterResults($results->getWriterResults());
        $this->jobRepository->save($job);
    }

    public function loadJobById(int $id): ?Job
    {
        return $this->jobRepository->findById($id);
    }

    public function countJobs(): int
    {
        return $this->jobRepository->count();
    }

    public function loadJobs($limit = 10, $offset = 0): array
    {
        return $this->jobRepository->find($limit, $offset);
    }
}
