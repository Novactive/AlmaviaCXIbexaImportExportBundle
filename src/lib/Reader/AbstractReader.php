<?php

declare(strict_types=1);

namespace AlmaviaCX\Bundle\IbexaImportExport\Reader;

use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

abstract class AbstractReader implements ReaderInterface
{
    public function getName(): string
    {
        return sprintf('reader.%s', $this->getIdentifier());
    }

    protected function resolveOptions(array $options): array
    {
        $optionsResolver = new OptionsResolver();
        $this->configureOptions($optionsResolver);

        return $optionsResolver->resolve($options);
    }

    protected function configureOptions(OptionsResolver $optionsResolver)
    {
    }

    public function mapJobForm(FormBuilderInterface $formBuilder): void
    {
    }

    public function mapConfigurationForm(FormBuilderInterface $formBuilder): void
    {
    }
}
