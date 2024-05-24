<?php

declare(strict_types=1);

namespace AlmaviaCX\Bundle\IbexaImportExport\Reader\Csv;

use AlmaviaCX\Bundle\IbexaImportExport\Reader\ReaderOptions;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class CsvReaderOptions extends ReaderOptions
{
    /** @var string|UploadedFile */
    protected $file;

    /**
     * @return string|\Symfony\Component\HttpFoundation\File\UploadedFile
     */
    public function getFile()
    {
        return $this->file;
    }

    /**
     * @param string|\Symfony\Component\HttpFoundation\File\UploadedFile $file
     */
    public function setFile($file): CsvReaderOptions
    {
        $this->file = $file;

        return $this;
    }
}
