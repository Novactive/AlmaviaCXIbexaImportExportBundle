<?php
declare( strict_types=1 );

namespace AlmaviaCX\Bundle\IbexaImportExport\Reader;

class ReaderFactoryRegistry
{
    /**
     * @var \AlmaviaCX\Bundle\IbexaImportExport\Reader\ReaderFactoryInterface[]
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

    public function getFactory( string $identifier  ): ReaderFactoryInterface
    {
        return $this->factories[$identifier];
    }
}
