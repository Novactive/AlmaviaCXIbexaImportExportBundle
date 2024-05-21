<?php

declare(strict_types=1);

namespace AlmaviaCX\Bundle\IbexaImportExport\Writer;

use Ibexa\Core\Base\Exceptions\NotFoundException;

class WriterRegistry
{
    /**
     * @var \AlmaviaCX\Bundle\IbexaImportExport\Writer\WriterInterface[]
     */
    protected array $writers = [];

    public function __construct(
        iterable $writers
    ) {
        foreach ($writers as $writer) {
            $this->registerWriter($writer);
        }
    }

    public function registerWriter(WriterInterface $writer)
    {
        $this->writers[$writer->getIdentifier()] = $writer;
    }

    /**
     * @throws \Ibexa\Core\Base\Exceptions\NotFoundException
     */
    public function get(string $identifier): WriterInterface
    {
        if (!isset($this->writers[$identifier])) {
            throw new NotFoundException(
                'WriterInterface',
                $identifier,
            );
        }

        return $this->writers[$identifier];
    }
}
