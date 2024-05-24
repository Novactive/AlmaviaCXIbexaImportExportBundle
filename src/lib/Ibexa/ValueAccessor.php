<?php

declare(strict_types=1);

namespace AlmaviaCX\Bundle\IbexaImportExport\Ibexa;

use AlmaviaCX\Bundle\IbexaImportExport\Ibexa\Content\ContentAccessor;
use Ibexa\Contracts\Core\Repository\Exceptions\PropertyNotFoundException;
use Ibexa\Contracts\Core\Repository\Values\Content\Location;
use Ibexa\Contracts\Core\Repository\Values\ContentType\ContentType;
use ReflectionClass;
use ReflectionProperty;
use Symfony\Component\PropertyAccess\Exception\NoSuchIndexException;
use Symfony\Component\PropertyAccess\Exception\NoSuchPropertyException;
use Symfony\Component\PropertyAccess\PropertyAccess;
use Symfony\Component\PropertyAccess\PropertyAccessor;
use Symfony\Component\PropertyAccess\PropertyPath;
use Symfony\Component\VarExporter\LazyGhostTrait;

class ValueAccessor
{
    use LazyGhostTrait;

    public ContentAccessor $content;
    public Location $mainLocation;
    public array $locations = [];
    public ContentType $contentType;

    protected function getPropertyAccessor(): PropertyAccessor
    {
        return PropertyAccess::createPropertyAccessorBuilder()
                             ->disableExceptionOnInvalidPropertyPath()
                             ->disableExceptionOnInvalidIndex()
                             ->getPropertyAccessor();
    }

    public function getValue(string $propertyPath)
    {
        $propertyPath = new PropertyPath($propertyPath);

        try {
            $value = $this->getPropertyAccessor()->getValue($this, $propertyPath);
        } catch (NoSuchIndexException|NoSuchPropertyException  $exception) {
            return null;
        }

        return $value;
    }

    public function getAvailableProperties(): array
    {
        return $this->getPropertiesPaths($this);
    }

    protected function getPropertiesPaths($instance, string $prefix = ''): array
    {
        $reflectionClass = new ReflectionClass($instance);
        $properties = [];
        foreach ($reflectionClass->getProperties(ReflectionProperty::IS_PUBLIC) as $property) {
            try {
                $propertyName = $property->getName();

                $properties = array_merge(
                    $properties,
                    $this->getPropertyPaths(
                        $instance->{$propertyName},
                        sprintf('%s%s', $prefix, $propertyName)
                    )
                );
            } catch (PropertyNotFoundException $exception) {
                continue;
            }
        }

        return $properties;
    }

    protected function getPropertyPaths($value, string $propertyName): array
    {
        $paths = [];
        if (is_array($value)) {
            foreach ($value as $index => $item) {
                $itemPropertyPaths = $this->getPropertyPaths(
                    $item,
                    sprintf('%s[%s]', $propertyName, $index)
                );
                if (!empty($itemPropertyPaths)) {
                    $paths = array_merge($paths, $itemPropertyPaths);
                }
            }

            return $paths;
        }

        if (is_object($value)) {
            return array_merge(
                $paths,
                $this->getPropertiesPaths(
                    $value,
                    sprintf('%s.', $propertyName)
                )
            );
        }

        $paths[] = $propertyName;

        return $paths;
    }
}
