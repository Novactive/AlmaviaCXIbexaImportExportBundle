<?php

declare(strict_types=1);

namespace AlmaviaCX\Bundle\IbexaImportExport\Step;

use AlmaviaCX\Bundle\IbexaImportExport\Processor\ProcessorOptionsFormType;
use Symfony\Component\OptionsResolver\OptionsResolver;

abstract class StepOptionsFormFormType extends ProcessorOptionsFormType
{
    public function configureOptions(OptionsResolver $resolver)
    {
        parent::configureOptions($resolver);
        $resolver->setDefaults([
                                    'data_class' => AbstractStep::getOptionsType(),
                                ]);
    }
}
