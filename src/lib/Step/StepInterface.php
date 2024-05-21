<?php

declare(strict_types=1);

namespace AlmaviaCX\Bundle\IbexaImportExport\Step;

use Symfony\Component\Form\FormBuilderInterface;

interface StepInterface
{
    public function getIdentifier(): string;

    public function getName(): string;

    public function __invoke($item, array $options);

    public function mapConfigurationForm(FormBuilderInterface $formBuilder): void;

    public function mapJobForm(FormBuilderInterface $formBuilder): void;
}
