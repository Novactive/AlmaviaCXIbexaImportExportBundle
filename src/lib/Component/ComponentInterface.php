<?php

declare(strict_types=1);

namespace AlmaviaCX\Bundle\IbexaImportExport\Component;

use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Translation\TranslatableMessage;

interface ComponentInterface
{
    public function getIdentifier(): string;

    /**
     * @return string|TranslatableMessage
     */
    public static function getName();

    public function mapConfigurationForm(FormBuilderInterface $formBuilder): void;

    public static function getOptionsFormType(): ?string;

    public static function getOptionsType(): ?string;

    public function setOptions(ComponentOptions $options): void;

    public function getOptions(): ComponentOptions;
}
