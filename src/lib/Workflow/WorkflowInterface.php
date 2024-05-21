<?php

declare(strict_types=1);

namespace AlmaviaCX\Bundle\IbexaImportExport\Workflow;

use AlmaviaCX\Bundle\IbexaImportExport\Result\Result;
use Symfony\Component\Form\FormBuilderInterface;

interface WorkflowInterface
{
    public function getIdentifier(): string;

    public function getName(): string;

    public function __invoke(array $options): Result;

    public function mapJobForm(FormBuilderInterface $formBuilder): void;
}
