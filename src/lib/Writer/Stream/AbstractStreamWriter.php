<?php

declare(strict_types=1);

namespace AlmaviaCX\Bundle\IbexaImportExport\Writer\Stream;

use AlmaviaCX\Bundle\IbexaImportExport\File\FileHandler;
use AlmaviaCX\Bundle\IbexaImportExport\Writer\AbstractWriter;
use AlmaviaCX\Bundle\IbexaImportExport\Writer\WriterResults;
use League\Flysystem\Config;

abstract class AbstractStreamWriter extends AbstractWriter
{
    /**
     * @var resource
     */
    protected $stream;
    protected FileHandler $fileHandler;

    public function __construct(FileHandler $fileHandler)
    {
        $this->fileHandler = $fileHandler;
    }

    public function prepare()
    {
        $this->stream = tmpfile();
    }

    public function finish(): WriterResults
    {
        /** @var \AlmaviaCX\Bundle\IbexaImportExport\Writer\Stream\StreamWriterOptions $options */
        $options = $this->getOptions();

        $filepath = ($this->fileHandler)->resolvePath($options->getFilepath());
        $this->fileHandler->writeStream($filepath, $this->stream, new Config());

        if (is_resource($this->stream)) {
            fclose($this->stream);
        }

        return new WriterResults(static::class, ['filepath' => $filepath]);
    }

    public static function getOptionsFormType(): ?string
    {
        return StreamWriterOptionsFormType::class;
    }

    public static function getOptionsType(): ?string
    {
        return StreamWriterOptions::class;
    }
}
