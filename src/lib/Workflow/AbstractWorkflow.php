<?php

declare(strict_types=1);

namespace AlmaviaCX\Bundle\IbexaImportExport\Workflow;

use AlmaviaCX\Bundle\IbexaImportExport\Result\Result;
use AlmaviaCX\Bundle\IbexaImportExport\Workflow\Form\Type\WorkflowProcessConfigurationFormType;
use DateTimeImmutable;
use Psr\Log\LoggerAwareTrait;

abstract class AbstractWorkflow implements WorkflowInterface
{
    use LoggerAwareTrait;

    protected WorkflowRunConfiguration $configuration;

    /**
     * @param \AlmaviaCX\Bundle\IbexaImportExport\Workflow\WorkflowRunConfiguration $configuration
     */
    public function setConfiguration(WorkflowRunConfiguration $configuration): void
    {
        $this->configuration = $configuration;
    }

    public function __invoke(): Result
    {
        $count = 0;
        $exceptions = [];
        $writerResults = [];
        $startTime = new DateTimeImmutable();

        try {
            $writers = $this->configuration->getWriters();
            foreach ($writers as $index => $writer) {
                $writer->prepare();
            }

            $reader = $this->configuration->getReader();
            foreach (($reader)() as $index => $item) {
                try {
                    foreach ($this->configuration->getProcessors() as $processor) {
                        $item = ($processor)($item);
                    }
                } catch (\Throwable $e) {
                    $exceptionIndex = $index;
                    $exceptions[$exceptionIndex] = $e->getMessage();
                    $this->logException($e, $exceptionIndex);
                }

                ++$count;
            }

            foreach ($writers as $index => $writer) {
                $writerResults[$index] = $writer->finish();
            }
        } catch (\Throwable $e) {
            $exceptions[] = $e->getMessage();
            $this->logException($e);
        }

        return new Result(
            $startTime,
            new DateTimeImmutable(),
            $count,
            $exceptions,
            $writerResults
        );
    }

    abstract public static function getDefaultConfig(): WorkflowConfiguration;

    /**
     * @param string|int|null $index
     */
    protected function logException(\Throwable $e, $index = null): void
    {
        if (!isset($this->logger)) {
            return;
        }

        $this->logger->error($e, ['exception' => $e, 'index' => $index]);
    }

    public static function getConfigurationFormType(): ?string
    {
        return WorkflowProcessConfigurationFormType::class;
    }

    //        $reader = $this->getReader();
    //        $reader->mapJobForm( $formBuilder );
    //
    //        $writers = $this->getWriters();
    //        if ( !empty( $writers ) )
    //        {
    //            $writersForm = $formBuilder->create( 'writers', FormType::class );
    //            foreach ( $writers as $index => $writer )
    //            {
    //                $writerForm = $writersForm->create( $index, FormType::class );
    //                $writer->mapJobForm( $writerForm );
    //                if ( $writerForm->count() > 0 )
    //                {
    //                    $writersForm->add( $writerForm );
    //                }
    //            }
    //            if ( $writersForm->count() > 0 )
    //            {
    //                $formBuilder->add( $writersForm );
    //            }
    //        }
    //
    //        $steps = $this->getSteps();
    //        if ( !empty( $steps ) )
    //        {
    //            $stepsForm = $formBuilder->create( 'steps', FormType::class );
    //            foreach ( $steps as $index => $step )
    //            {
    //                $stepForm = $stepsForm->create( $index, FormType::class );
    //                $step->mapJobForm( $stepForm );
    //                if ( $stepForm->count() > 0 )
    //                {
    //                    $stepsForm->add( $stepForm );
    //                }
    //            }
    //            if ( $stepsForm->count() > 0 )
    //            {
    //                $formBuilder->add( $stepsForm );
    //            }
    //        }
    //    }
}
