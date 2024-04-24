<?php
declare( strict_types=1 );

namespace AlmaviaCX\Bundle\IbexaImportExport\Workflow;

use Port\Workflow;

class WorkflowExecutor
{
    /**
     * @throws \Port\Exception
     */
    public function __invoke( WorkflowFactoryInterface $workflowFactory, array $options): \Port\Result
    {
        $workflow = ($workflowFactory)( $options);
        return $workflow->process();
    }
}
