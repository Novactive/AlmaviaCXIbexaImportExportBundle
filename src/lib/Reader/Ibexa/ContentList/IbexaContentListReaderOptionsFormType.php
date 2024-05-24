<?php

declare(strict_types=1);

namespace AlmaviaCX\Bundle\IbexaImportExport\Reader\Ibexa\ContentList;

use AlmaviaCX\Bundle\IbexaImportExport\Reader\ReaderOptionsFormType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Yaml\Yaml;

class IbexaContentListReaderOptionsFormType extends ReaderOptionsFormType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('parentLocationId', IntegerType::class, [
            'label' => /* @Desc("Parent location id") */ 'reader.ibexa.content_list.options.parentLocationId.label',
        ]);
        $builder->add(
            $builder->create('map', TextareaType::class, [
                'label' => /* @Desc("Map") */ 'reader.ibexa.content_list.options.map.label',
            ])
                    ->addModelTransformer(
                        new CallbackTransformer(
                            function (?array $value) {
                                return Yaml::dump(['map' => $value ?? []]);
                            },
                            function (?string $value) {
                                return $value ? Yaml::parse($value)['map'] : [];
                            }
                        )
                    )
        );
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        parent::configureOptions($resolver);
        $resolver->setDefaults([
            'data_class' => IbexaContentListReader::getOptionsType(),
                               ]);
    }
}
