<?php

declare(strict_types=1);

namespace AlmaviaCX\Bundle\IbexaImportExport\Writer;

use AlmaviaCX\Bundle\IbexaImportExport\Resolver\WriterFilepathResolver;
use Symfony\Component\Filesystem\Filesystem;
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

    protected WriterFilepathResolver $filepathResolver;
    protected Filesystem $filesystem;

    public function __construct(Filesystem $filesystem, WriterFilepathResolver $filepathResolver)
    {
        $this->filesystem = $filesystem;
        $this->filepathResolver = $filepathResolver;
    }

    public function prepare(array $options = [])
    {
        $this->stream = tmpfile();
    }

    public function finish(array $options = []): WriterResults
    {
        $streamMetadatas = stream_get_meta_data($this->stream);

        $options = $this->resolveOptions($options);
        $filepath = ($this->filepathResolver)($options['filepath']);
        $this->filesystem->copy($streamMetadatas['uri'], $filepath);

        if (is_resource($this->stream)) {
            fclose($this->stream);
        }

        return new WriterResults($this->getIdentifier(), ['filepath' => $filepath]);
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
