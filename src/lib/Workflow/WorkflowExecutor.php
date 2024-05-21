<?php

declare(strict_types=1);

namespace AlmaviaCX\Bundle\IbexaImportExport\Workflow;

use AlmaviaCX\Bundle\IbexaImportExport\Result\Result;

class WorkflowExecutor
{
    /**
     * @throws \Port\Exception
     */
    public function __invoke(WorkflowInterface $workflow, array $options): Result
    {
        return ($workflow)($options);
    }
}
