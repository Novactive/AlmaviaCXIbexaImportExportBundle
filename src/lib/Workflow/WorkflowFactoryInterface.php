<?php
declare( strict_types=1 );

namespace AlmaviaCX\Bundle\IbexaImportExport\Workflow;

use Port\Workflow;

interface WorkflowFactoryInterface
{
    public function __invoke(array $options): Workflow;
}
