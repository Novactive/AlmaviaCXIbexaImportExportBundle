<?php

declare(strict_types=1);

namespace AlmaviaCX\Bundle\IbexaImportExport\Component;

use InvalidArgumentException;

class ComponentReference
{
    protected ?ComponentOptions $options = null;

    public function __construct(
        protected string $type,
        ?ComponentOptions $options = null
    ) {
        $requiredOptionsType = call_user_func([$type, 'getOptionsType']);
        if (!$options && $requiredOptionsType) {
            $options = new $requiredOptionsType();
        }
        if (!$options instanceof $requiredOptionsType) {
            throw new InvalidArgumentException('Options must be an instance of '.$requiredOptionsType);
        }
        $this->options = $options;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function getOptions(): ?ComponentOptions
    {
        return $this->options;
    }
}
