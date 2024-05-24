<?php

declare(strict_types=1);

namespace AlmaviaCX\Bundle\IbexaImportExport\Workflow;

use AlmaviaCX\Bundle\IbexaImportExport\Result\Result;

interface WorkflowInterface
{
    public function __invoke(): Result;

    public function setConfiguration(WorkflowRunConfiguration $configuration): void;

    public static function getDefaultConfig(): WorkflowConfiguration;

    public static function getConfigurationFormType(): ?string;
}
