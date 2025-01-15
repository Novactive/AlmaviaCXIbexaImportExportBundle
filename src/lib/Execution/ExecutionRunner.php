<?php

declare(strict_types=1);

namespace AlmaviaCX\Bundle\IbexaImportExport\Execution;

use AlmaviaCX\Bundle\IbexaImportExport\Event\PostJobRunEvent;
use AlmaviaCX\Bundle\IbexaImportExport\Event\PreJobRunEvent;
use AlmaviaCX\Bundle\IbexaImportExport\Workflow\WorkflowEvent;
use AlmaviaCX\Bundle\IbexaImportExport\Workflow\WorkflowExecutor;
use AlmaviaCX\Bundle\IbexaImportExport\Workflow\WorkflowInterface;
use AlmaviaCX\Bundle\IbexaImportExport\Workflow\WorkflowRegistry;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;

class ExecutionRunner
{
    public function __construct(
        protected WorkflowExecutor $workflowExecutor,
        protected WorkflowRegistry $workflowRegistry,
        protected EventDispatcherInterface $eventDispatcher,
        protected ExecutionRepository $executionRepository
    ) {
    }

    public function __invoke(Execution $execution, int $batchLimit = -1): int
    {
        $workflow = $this->workflowRegistry->getWorkflow($execution->getWorkflowIdentifier());

        $onWorkflowProgress = function (WorkflowEvent $event) use ($execution) {
            $workflow = $event->getWorkflow();
            $this->updateExecution($execution, $workflow);
            $event->setContinue($execution->isRunning());
        };
        $workflow->addEventListener(WorkflowEvent::PROGRESS, $onWorkflowProgress);
        $workflow->addEventListener(WorkflowEvent::START, $onWorkflowProgress);

        $this->eventDispatcher->dispatch(new PreJobRunEvent($execution, $workflow));

        if (null !== $execution->getWorkflowState()) {
            $workflow->setState($execution->getWorkflowState());
        }
        $execution->setStatus(Execution::STATUS_RUNNING);
        $this->executionRepository->save($execution);

        ($this->workflowExecutor)(
            $workflow,
            $this->buildExecutionOptions($execution),
            $batchLimit
        );

        $this->updateExecution($execution, $workflow);
        if ($workflow->getState()->isCompleted()) {
            $execution->setStatus(Execution::STATUS_COMPLETED);
        } elseif (Execution::STATUS_RUNNING === $execution->getStatus()) {
            $execution->setStatus(Execution::STATUS_PAUSED);
        }

        $this->eventDispatcher->dispatch(new PostJobRunEvent($execution, $workflow));

        $this->executionRepository->save($execution);

        return $execution->getStatus();
    }

    protected function buildExecutionOptions(Execution $execution): ExecutionOptions
    {
        $executionOptions = $execution->getOptions();
        $jobOptions = $execution->getJob()->getOptions();

        return $jobOptions->merge($executionOptions);
    }

    protected function updateExecution(Execution $execution, WorkflowInterface $workflow): void
    {
        /*
         * Ibexa content creations trigger an entity manager clear, which mean we need to reload the entity
         * The execution might have been paused or canceled during the workflow execution
         */
        $this->executionRepository->refresh($execution);

        $execution->addLoggerRecords($workflow->getLogger()->getRecords());
        $execution->setWorkflowState($workflow->getState());
        $this->executionRepository->save($execution);
    }
}
