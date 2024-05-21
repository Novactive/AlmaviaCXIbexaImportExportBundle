<?php

declare(strict_types=1);

namespace AlmaviaCX\Bundle\IbexaImportExport\Reader;

use Iterator;
use Symfony\Component\Form\FormBuilderInterface;

interface ReaderInterface
{
    public function getIdentifier(): string;

    public function getName(): string;

    public function __invoke(array $options): Iterator;

    public function mapConfigurationForm(FormBuilderInterface $formBuilder): void;

    public function mapJobForm(FormBuilderInterface $formBuilder): void;
}
