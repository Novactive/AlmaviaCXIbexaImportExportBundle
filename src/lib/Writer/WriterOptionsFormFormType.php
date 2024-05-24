<?php

declare(strict_types=1);

namespace AlmaviaCX\Bundle\IbexaImportExport\Writer;

use AlmaviaCX\Bundle\IbexaImportExport\Processor\ProcessorOptionsFormType;
use Symfony\Component\OptionsResolver\OptionsResolver;

abstract class WriterOptionsFormFormType extends ProcessorOptionsFormType
{
    public function configureOptions(OptionsResolver $resolver)
    {
        parent::configureOptions($resolver);
        $resolver->setDefaults([
                                    'data_class' => AbstractWriter::getOptionsType(),
                                ]);
    }
}
