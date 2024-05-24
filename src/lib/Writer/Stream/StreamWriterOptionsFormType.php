<?php

declare(strict_types=1);

namespace AlmaviaCX\Bundle\IbexaImportExport\Writer\Stream;

use AlmaviaCX\Bundle\IbexaImportExport\Resolver\FilepathResolver;
use AlmaviaCX\Bundle\IbexaImportExport\Writer\WriterOptionsFormFormType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Translation\TranslatableMessage;

class StreamWriterOptionsFormType extends WriterOptionsFormFormType
{
    protected FilepathResolver $filepathResolver;

    public function __construct(FilepathResolver $filepathResolver)
    {
        $this->filepathResolver = $filepathResolver;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $helpMsg = /* @Desc("Tokens") */ 'writer.stream.options.filepath.tokens';
        $tokens = implode(
            ' / ',
            array_keys($this->filepathResolver->buildTokens())
        );
        $builder->add('filepath', TextType::class, [
            'label' => /* @Desc("File path") */ 'writer.stream.options.filepath.label',
            'help' => new TranslatableMessage($helpMsg, ['%tokens%' => $tokens]),
        ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        parent::configureOptions($resolver);
        $resolver->setDefaults([
                                    'data_class' => AbstractStreamWriter::getOptionsType(),
                                ]);
    }
}
