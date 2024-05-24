<?php

declare(strict_types=1);

namespace AlmaviaCX\Bundle\IbexaImportExport\Component;

use ReflectionClass;

class ComponentOptions
{
    public function merge(array $overrideOptions): ComponentOptions
    {
        $reflection = new ReflectionClass($this);

        foreach ($reflection->getProperties() as $property) {
            if (!isset($overrideOptions[$property->getName()])) {
                continue;
            }
            $property->setValue($overrideOptions[$property->getName()]);
        }

        return $this;
    }
}
