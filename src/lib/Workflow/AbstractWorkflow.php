<?php

declare(strict_types=1);

namespace AlmaviaCX\Bundle\IbexaImportExport\Workflow;

use AlmaviaCX\Bundle\IbexaImportExport\Reader\ReaderInterface;
use AlmaviaCX\Bundle\IbexaImportExport\Result\Result;
use DateTimeImmutable;
use Psr\Log\LoggerAwareTrait;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

abstract class AbstractWorkflow implements WorkflowInterface
{
    use LoggerAwareTrait;

    public function getName(): string
    {
        return sprintf('workflow.%s', $this->getIdentifier());
    }

    public function __invoke(array $options): Result
    {
        $count = 0;
        $exceptions = [];
        $writerResults = [];
        $startTime = new DateTimeImmutable();

        $options = $this->resolveOptions($options);
        try {
            $writers = $this->getWriters();
            foreach ($writers as $index => $writer) {
                $writer->prepare($options['writers'][$index] ?? []);
            }

            $reader = $this->getReader();
            foreach (($reader)($options['reader']) as $index => $item) {
                try {
                    $steps = $this->getSteps();
                    foreach ($steps as $stepIndex => $step) {
                        $item = ($step)($item, $options['steps'][$stepIndex] ?? []);
                        if (false === $item) {
                            continue 2;
                        }
                    }

                    $writers = $this->getWriters();
                    foreach ($writers as $writerIndex => $writer) {
                        ($writer)($item, $options['writers'][$writerIndex] ?? []);
                    }
                } catch (\Throwable $e) {
                    $exceptionIndex = $index;
                    $exceptions[$exceptionIndex] = $e;
                    $this->logException($e, $exceptionIndex);
                }

                ++$count;
            }

            foreach ($writers as $index => $writer) {
                $writerResults[$index] = $writer->finish($options['writers'][$index] ?? []);
            }
        } catch (\Throwable $e) {
            $exceptions[] = $e;
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

    protected function resolveOptions(array $options): array
    {
        $optionsResolver = new OptionsResolver();
        $this->configureOptions($optionsResolver);

        return $optionsResolver->resolve($options);
    }

    protected function configureOptions(OptionsResolver $optionsResolver)
    {
        $optionsResolver->define('reader')
                        ->default([])
                        ->allowedTypes('array');

        $optionsResolver->define('writers')
                        ->default([])
                        ->allowedTypes('array');

        $optionsResolver->define('steps')
                        ->default([])
                        ->allowedTypes('array');
    }

    abstract protected function getReader(): ReaderInterface;

    /**
     * @return \AlmaviaCX\Bundle\IbexaImportExport\Writer\WriterInterface[]
     */
    abstract protected function getWriters(): array;

    /**
     * @return \AlmaviaCX\Bundle\IbexaImportExport\Step\StepInterface[]
     */
    abstract protected function getSteps(): array;

    public function mapJobForm(FormBuilderInterface $formBuilder): void
    {
        $reader = $this->getReader();
        $readerForm = $formBuilder->create('reader', FormType::class, [
            'label' => $reader->getName(),
        ]);
        $reader->mapJobForm($readerForm);
        if ($readerForm->count() > 0) {
            $formBuilder->add($readerForm);
        }

        $writers = $this->getWriters();
        if (!empty($writers)) {
            $writersForm = $formBuilder->create('writers', FormType::class);
            foreach ($writers as $index => $writer) {
                $writerForm = $writersForm->create($index, FormType::class);
                $writer->mapJobForm($writerForm);
                if ($writerForm->count() > 0) {
                    $writersForm->add($writerForm);
                }
            }
            if ($writersForm->count() > 0) {
                $formBuilder->add($writersForm);
            }
        }

        $steps = $this->getSteps();
        if (!empty($steps)) {
            $stepsForm = $formBuilder->create('steps', FormType::class);
            foreach ($steps as $index => $step) {
                $stepForm = $stepsForm->create($index, FormType::class);
                $step->mapJobForm($stepForm);
                if ($stepForm->count() > 0) {
                    $stepsForm->add($stepForm);
                }
            }
            if ($stepsForm->count() > 0) {
                $formBuilder->add($stepsForm);
            }
        }
    }
}
