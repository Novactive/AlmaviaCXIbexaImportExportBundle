<?php

declare(strict_types=1);

namespace AlmaviaCX\Bundle\IbexaImportExport\Writer;

use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CsvWriter extends AbstractStreamWriter
{
    private int $row = 1;

    public function getIdentifier(): string
    {
        return 'csv_writer';
    }

    public function prepare(array $options = [])
    {
        parent::prepare($options);
        $this->row = 1;
    }

    public function __invoke(array $item, array $options = []): void
    {
        $options = $this->resolveOptions($options);

        if ($options['prependHeaderRow'] && 1 == $this->row++) {
            $headers = array_keys($item);
            fputcsv($this->stream, $headers, $options['delimiter'], $options['enclosure']);
        }

        fputcsv($this->stream, $item, $options['delimiter'], $options['enclosure']);
    }

    protected function configureOptions(OptionsResolver $optionsResolver)
    {
        parent::configureOptions($optionsResolver);
        $optionsResolver->define('delimiter')->default(',')->allowedTypes('string');
        $optionsResolver->define('enclosure')->default('"')->allowedTypes('string');
        $optionsResolver->define('utf8Encoding')->default(false)->allowedTypes('boolean');
        $optionsResolver->define('prependHeaderRow')->default(false)->allowedTypes('boolean');
    }

    public function mapConfigurationForm(FormBuilderInterface $formBuilder): void
    {
        parent::mapConfigurationForm($formBuilder);

        $formBuilder->add('delimiter', ChoiceType::class, [
            'label' => 'writer.csv.delimiter',
            'choices' => [
                ',' => ',',
            ],
        ]);
        $formBuilder->add('enclosure', ChoiceType::class, [
            'label' => 'writer.csv.enclosure',
            'choices' => [
                '"' => '"',
            ],
        ]);
        $formBuilder->add('utf8Encoding', CheckboxType::class, [
            'label' => 'writer.csv.utf8Encoding',
            'required' => false,
        ]);
        $formBuilder->add('prependHeaderRow', CheckboxType::class, [
            'label' => 'writer.csv.prependHeaderRow',
            'required' => false,
        ]);
    }
}
