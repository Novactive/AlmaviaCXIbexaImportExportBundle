<?php
declare( strict_types=1 );

namespace AlmaviaCX\Bundle\IbexaImportExport\Workflow;


class WorkflowFactoryRegistry
{

    /**
     * @var \AlmaviaCX\Bundle\IbexaImportExport\Workflow\WorkflowFactoryInterface[]
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

    public function getFactory( string $identifier): WorkflowFactoryInterface
    {
        $factory = $this->factories[$identifier];


        return $factory;
    }
}
