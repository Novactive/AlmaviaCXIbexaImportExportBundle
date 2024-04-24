<?php
declare( strict_types=1 );

namespace AlmaviaCX\Bundle\IbexaImportExport\Workflow;

use AlmaviaCX\Bundle\IbexaImportExport\Reader\ReaderFactoryRegistry;
use AlmaviaCX\Bundle\IbexaImportExport\Writer\WriterFactoryInterface;
use AlmaviaCX\Bundle\IbexaImportExport\Writer\WriterFactoryRegistry;
use Port\Reader;
use Port\Steps\StepAggregator as Workflow;
use Symfony\Component\OptionsResolver\OptionsResolver;

abstract class AbstractWorkflowFactory implements WorkflowFactoryInterface
{
    protected WriterFactoryRegistry $writerFactoryRegistry;
    protected ReaderFactoryRegistry $readerFactoryRegistry;

    public function __construct(
        ReaderFactoryRegistry $readerFactoryRegistry,
        WriterFactoryRegistry $writerFactoryRegistry,
    )
    {
        $this->readerFactoryRegistry = $readerFactoryRegistry;
        $this->writerFactoryRegistry = $writerFactoryRegistry;
    }

    public function __invoke( array $options ): \Port\Workflow
    {
        $optionsResolver = new OptionsResolver();
        $options = $optionsResolver->resolve( $options );

        $reader = $this->getReader($options['reader']);
        $writers = $this->getWriters($options['writers']);
        $steps = $this->getSteps($options['steps']);

        $workflow = new Workflow($reader);
        foreach ( $writers as $writer )
        {
            $workflow->addWriter($writer);
        }
        foreach ( $steps as $step )
        {
            $workflow->addStep($step);
        }
        return $workflow;
    }

    /**
     * @param array $options
     *
     * @return \Port\Reader
     */
    protected function getReader(array $options): Reader
    {
        $factory = $this->readerFactoryRegistry->getFactory($options['identifier']);
        return ($factory)($options);
    }

    /**
     * @param array $writersOptions
     *
     * @return \Port\Writer[]
     */
    protected function getWriter(array $writersOptions): \Generator
    {
        foreach ( $writersOptions as $options) {
            $factory = $this->readerFactoryRegistry->getFactory( $options['identifier']);
            yield ($factory)( $writersOptions);
        }
    }

    /**
     * @param array $options
     *
     * @return \Port\Steps\Step[]
     */
    abstract protected function getSteps(array $options): \Generator;

    protected function configureOptions( OptionsResolver $optionsResolver)
    {
        $optionsResolver->define('reader')
            ->default([]);

        $optionsResolver->define('writers')
            ->default([]);

        $optionsResolver->define('steps')
            ->default([]);
    }
}
