<?php

declare(strict_types=1);

namespace AlmaviaCX\Bundle\IbexaImportExport\Job;

use AlmaviaCX\Bundle\IbexaImportExport\Execution\Execution;
use AlmaviaCX\Bundle\IbexaImportExport\Execution\ExecutionRunner;

class JobRunner extends AbstractJobRunner
{
    public function __construct(
        protected ExecutionRunner $executionRunner,
    ) {
    }

    public function runExecution(Execution $execution, int $batchLimit = -1): int
    {
        if (!$execution->canRun()) {
            return $execution->getStatus();
        }

        return ($this->executionRunner)($execution, $batchLimit);
    }
}
