<?php
declare( strict_types=1 );

namespace AlmaviaCX\Bundle\IbexaImportExport\Writer;


class WriterFactoryRegistry
{
    /**
     * @var \AlmaviaCX\Bundle\IbexaImportExport\Writer\WriterFactoryInterface[]
     */
    protected array $factories = [];

    public function __construct(
        iterable $factories
    )
    {
        foreach ( $factories as $identifier => $workflow) {
            $this->factories[$identifier] = $workflow;
        }
    }

    public function getFactory( string $identifier  ): WriterFactoryInterface
    {
        return $this->factories[$identifier];
    }
}
