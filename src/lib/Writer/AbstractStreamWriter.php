<?php

declare(strict_types=1);

namespace AlmaviaCX\Bundle\IbexaImportExport\Writer;

use AlmaviaCX\Bundle\IbexaImportExport\Resolver\WriterFilepathResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Translation\TranslatableMessage;

abstract class AbstractStreamWriter extends AbstractWriter
{
    /**
     * @var resource
     */
    protected $stream;
    protected string $filepath;

    protected WriterFilepathResolver $filepathResolver;

    public function __construct(WriterFilepathResolver $filepathResolver)
    {
        $this->filepathResolver = $filepathResolver;
    }

    public function prepare(array $options = [])
    {
        $this->filepath = ($this->filepathResolver)($options['filepath']);
        $this->stream = fopen($this->filepath, 'w');
    }

    public function finish(array $options = []): WriterResults
    {
        if (is_resource($this->stream)) {
            fclose($this->stream);
        }

        return new WriterResults($this->getIdentifier(), ['filepath' => $this->filepath]);
    }

    protected function configureOptions(OptionsResolver $optionsResolver)
    {
        parent::configureOptions($optionsResolver);
        $optionsResolver->define('filepath')
                        ->required()
                        ->allowedTypes('string')
                        ->info('File path where to store the generated file');
    }

    public function mapConfigurationForm(FormBuilderInterface $formBuilder): void
    {
        parent::mapConfigurationForm($formBuilder);
        $formBuilder->add('filepath', TextType::class, [
            'label' => 'writer.stream.filepath',
            'help' => new TranslatableMessage(
                'writer.stream.filepath_tokens',
                [
                    '%tokens%' => implode(
                        ' / ',
                        array_keys($this->filepathResolver->buildTokens())
                    ),
                ]
            ),
        ]);
    }
}
