<?php

declare(strict_types=1);

namespace AlmaviaCX\Bundle\IbexaImportExport\Workflow;

use AlmaviaCX\Bundle\IbexaImportExport\Processor\ProcessorOptions;
use AlmaviaCX\Bundle\IbexaImportExport\Reader\ReaderOptions;
use Doctrine\ORM\Mapping as ORM;
use InvalidArgumentException;

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
     * @ORM\Column
     */
    protected WorkflowProcessConfiguration $processConfiguration;

    public function __construct(string $identifier, string $name)
    {
        $this->identifier = $identifier;
        $this->name = $name;
        $this->processConfiguration = new WorkflowProcessConfiguration();
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

    public function getProcessConfiguration(): WorkflowProcessConfiguration
    {
        return $this->processConfiguration;
    }

    public function setProcessConfiguration(WorkflowProcessConfiguration $processConfiguration): void
    {
        $this->processConfiguration = $processConfiguration;
    }

    public function setReader(string $class, ReaderOptions $options = null)
    {
        $requiredOptionsType = call_user_func([$class, 'getOptionsType']);
        if (!$options) {
            $options = new $requiredOptionsType();
        }
        if (!$options instanceof $requiredOptionsType) {
            throw new InvalidArgumentException('Options must be an instance of '.$requiredOptionsType);
        }
        $this->processConfiguration->setReader([
                                                    'type' => $class,
                                                    'options' => $options,
                                                ]);
    }

    public function addProcessor(string $class, ProcessorOptions $options = null)
    {
        $requiredOptionsType = call_user_func([$class, 'getOptionsType']);
        if (!$options) {
            $options = new $requiredOptionsType();
        }
        if (!$options instanceof $requiredOptionsType) {
            throw new InvalidArgumentException('Options must be an instance of '.$requiredOptionsType);
        }
        $this->processConfiguration->addProcessor([
                                                       'type' => $class,
                                                       'options' => $options,
                                                   ]);
    }

    /**
     * @return array{type: string, options: ReaderOptions}
     */
    public function getReader(): array
    {
        return $this->processConfiguration->getReader();
    }

    /**
     * @return array<array{type: string, options: ProcessorOptions}>
     */
    public function getProcessors(): array
    {
        return $this->processConfiguration->getProcessors();
    }
}
