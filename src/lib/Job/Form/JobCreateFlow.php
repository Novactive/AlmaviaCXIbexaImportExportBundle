<?php

declare(strict_types=1);

namespace AlmaviaCX\Bundle\IbexaImportExport\Job\Form;

use AlmaviaCX\Bundle\IbexaImportExport\Job\Form\Type\JobCreateType;
use Craue\FormFlowBundle\Form\FormFlow;

class JobCreateFlow extends FormFlow
{
    protected function loadStepsConfig()
    {
        return [
            [
                'label' => '1',
                'form_type' => JobCreateType::class,
            ],
            [
                'label' => '2',
                'form_type' => JobCreateType::class,
            ],
        ];
    }
}
