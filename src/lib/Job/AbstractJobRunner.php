<?php

declare(strict_types=1);

namespace AlmaviaCX\Bundle\IbexaImportExport\Job;

use AlmaviaCX\Bundle\IbexaImportExport\Execution\Execution;

abstract class AbstractJobRunner implements JobRunnerInterface
{
    public function __invoke(Job $job, int $batchLimit = -1, bool $reset = false): int
    {
        $execution = $job->getLastExecution();
        if ($reset && $execution) {
            $execution->setStatus(Execution::STATUS_CANCELED);
        }

        if (!$execution || $execution->isDone()) {
            $execution = new Execution();
            $job->addExecution($execution);
        }

        if (!$execution->canRun()) {
            return $execution->getStatus();
        }

        return $this->runExecution($execution, $batchLimit);
    }

    abstract public function runExecution(Execution $execution, int $batchLimit = -1): int;
}
