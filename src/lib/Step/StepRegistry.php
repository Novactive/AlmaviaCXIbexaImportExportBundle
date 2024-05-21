<?php

declare(strict_types=1);

namespace AlmaviaCX\Bundle\IbexaImportExport\Step;

class StepRegistry
{
    /**
     * @var \AlmaviaCX\Bundle\IbexaImportExport\Step\StepInterface[]
     */
    protected array $steps = [];

    public function __construct(
        iterable $steps
    ) {
        foreach ($steps as $step) {
            $this->registerStep($step);
        }
    }

    public function registerStep(StepInterface $step)
    {
        $this->steps[$step->getIdentifier()] = $step;
    }

    public function get(string $identifier): StepInterface
    {
        return $this->steps[$identifier];
    }
}
