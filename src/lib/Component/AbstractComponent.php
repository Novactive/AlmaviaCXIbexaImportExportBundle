<?php

declare(strict_types=1);

namespace AlmaviaCX\Bundle\IbexaImportExport\Component;

use InvalidArgumentException;
use Symfony\Component\Form\FormBuilderInterface;

abstract class AbstractComponent implements ComponentInterface
{
    protected ComponentOptions $options;

    public function mapConfigurationForm(FormBuilderInterface $formBuilder): void
    {
        // TODO: Implement mapConfigurationForm() method.
    }

    public static function getOptionsFormType(): ?string
    {
        return null;
    }

    public static function getOptionsType(): ?string
    {
        return ComponentOptions::class;
    }

    /**
     * @param \AlmaviaCX\Bundle\IbexaImportExport\Component\ComponentOptions $options
     */
    public function setOptions(ComponentOptions $options): void
    {
        $requiredOptionType = static::getOptionsType();
        if (!$options instanceof $requiredOptionType) {
            throw new InvalidArgumentException('Options must be an instance of '.$requiredOptionType);
        }
        $this->options = $options;
    }

    public function getOptions(): ComponentOptions
    {
        return $this->options;
    }
}
