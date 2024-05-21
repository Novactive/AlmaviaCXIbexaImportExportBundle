<?php

declare(strict_types=1);

namespace AlmaviaCX\Bundle\IbexaImportExport\Job;

use AlmaviaCX\Bundle\IbexaImportExport\Event\PreJobRunEvent;
use AlmaviaCX\Bundle\IbexaImportExport\Result\Result;
use AlmaviaCX\Bundle\IbexaImportExport\Workflow\WorkflowExecutor;
use AlmaviaCX\Bundle\IbexaImportExport\Workflow\WorkflowRegistry;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;

class JobRunner
{
    protected WorkflowExecutor $workflowExecutor;
    protected WorkflowRegistry $workflowRegistry;
    protected EventDispatcherInterface $eventDispatcher;

    public function __construct(
        WorkflowExecutor $workflowExecutor,
        WorkflowRegistry $workflowRegistry,
        EventDispatcherInterface $eventDispatcher
    ) {
        $this->eventDispatcher = $eventDispatcher;
        $this->workflowRegistry = $workflowRegistry;
        $this->workflowExecutor = $workflowExecutor;
    }

    public function __invoke(Job $job): Result
    {
        $workflow = $this->workflowRegistry->get($job->getWorkflowIdentifier());
        $this->eventDispatcher->dispatch(new PreJobRunEvent($job, $workflow));

        $results = ($this->workflowExecutor)($workflow, $job->getOptions());

        $this->eventDispatcher->dispatch(new PreJobRunEvent($job, $workflow, $results));

        return $results;
    }
}
