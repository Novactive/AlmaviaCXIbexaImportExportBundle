<?php

declare(strict_types=1);

namespace AlmaviaCX\Bundle\IbexaImportExport\Workflow;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 * @ORM\Table(name="import_export_workflow_configuration")
 */
class WorkflowConfiguration
{
    /**
     * @ORM\Id
     * @ORM\Column(type="string")
     */
    protected string $identifier;

    /**
     * @ORM\Column
     */
    protected string $name;

    /**
     * @ORM\Column(type="array")
     */
    protected array $configuration;

    public function __construct(string $identifier, string $name, array $configuration)
    {
        $this->identifier = $identifier;
        $this->name = $name;
        $this->configuration = $configuration;
    }

    public function getIdentifier(): string
    {
        return $this->identifier;
    }

    public function setIdentifier(string $identifier): void
    {
        $this->identifier = $identifier;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getConfiguration(): array
    {
        return $this->configuration;
    }

    public function setConfiguration(array $configuration): void
    {
        $this->configuration = $configuration;
    }

    public function getReaderIdentifier(): string
    {
        return $this->configuration['reader']['identifier'];
    }
}
