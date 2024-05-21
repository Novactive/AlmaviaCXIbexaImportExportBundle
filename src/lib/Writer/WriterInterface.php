<?php

declare(strict_types=1);

namespace AlmaviaCX\Bundle\IbexaImportExport\Writer;

use Symfony\Component\Form\FormBuilderInterface;

interface WriterInterface
{
    public function getIdentifier(): string;

    public function getName(): string;

    public function prepare(array $options = []);

    public function __invoke(array $item, array $options = []): void;

    public function finish(array $options = []): WriterResults;

    public function mapConfigurationForm(FormBuilderInterface $formBuilder): void;

    public function mapJobForm(FormBuilderInterface $formBuilder): void;
}
