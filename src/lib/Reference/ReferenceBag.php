<?php

declare(strict_types=1);

namespace AlmaviaCX\Bundle\IbexaImportExport\Reference;

class ReferenceBag
{
    /** @var array<int, array<string, mixed>> */
    protected array $references = [];

    public function addReference(string $name, mixed $value, int $scope = Reference::SCOPE_ITEM): void
    {
        if (!isset($this->references[$scope])) {
            $this->references[$scope] = [];
        }
        $this->references[$scope][$name] = $value;
    }

    public function hasReference(string $name, int $scope = Reference::SCOPE_ITEM): bool
    {
        return isset($this->references[$scope][$name]);
    }

    public function getReference(string $name, mixed $default = null, int $scope = Reference::SCOPE_ITEM): mixed
    {
        return $this->references[$scope][$name] ?? $default;
    }

    public function resetScope(int $scope): void
    {
        unset($this->references[$scope]);
    }

    public function __set(string $name, mixed $value): void
    {
        $this->addReference($name, $value);
    }

    public function __get(string $name): mixed
    {
        return $this->getReference($name);
    }

    public function __isset(string $name): bool
    {
        return $this->hasReference($name);
    }
}
