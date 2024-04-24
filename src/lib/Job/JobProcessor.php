<?php
declare( strict_types=1 );

namespace AlmaviaCX\Bundle\IbexaImportExport\Job;

use AlmaviaCX\Bundle\IbexaImportExport\Workflow\WorkflowExecutor;
use AlmaviaCX\Bundle\IbexaImportExport\Workflow\WorkflowFactoryRegistry;

class JobProcessor
{
    protected WorkflowExecutor $workflowExecutor;
    protected WorkflowFactoryRegistry $workflowFactoryRegistry;
    protected JobRepository $repository;

    public function __construct(
        WorkflowExecutor        $workflowExecutor,
        WorkflowFactoryRegistry $workflowFactoryRegistry,
        JobRepository           $repository
    )
    {
        $this->repository = $repository;
        $this->workflowFactoryRegistry = $workflowFactoryRegistry;
        $this->workflowExecutor = $workflowExecutor;
    }

    public function __invoke(Job $job)
    {
        $workflowFactory = $this->workflowFactoryRegistry->getFactory( $job->getWorkflowIdentifier());

        $job->setStatus(Job::STATUS_RUNNING);
        $job->setStartTime(new \DateTime());
        $this->repository->save($job);

        $workflowResults = ($this->workflowExecutor)($workflowFactory, $job->getOptions());

        $job->setStatus(Job::STATUS_COMPLETED);
        $job->setEndTime($workflowResults->getEndTime());
        $this->repository->save($job);


    }
}
