<?php

declare(strict_types=1);

namespace AlmaviaCX\Bundle\IbexaImportExport\Component;

use Psr\Container\ContainerInterface;

class ComponentRegistry
{
    protected ContainerInterface $typeContainer;

    public function __construct(ContainerInterface $typeContainer)
    {
        $this->typeContainer = $typeContainer;
    }

    public function getComponent(string $type): ComponentInterface
    {
        return $this->typeContainer->get($type);
    }
}
