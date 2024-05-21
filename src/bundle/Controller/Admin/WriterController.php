<?php

declare(strict_types=1);

namespace AlmaviaCX\Bundle\IbexaImportExportBundle\Controller\Admin;

use AlmaviaCX\Bundle\IbexaImportExport\Job\Job;
use AlmaviaCX\Bundle\IbexaImportExport\Writer\AbstractStreamWriter;
use AlmaviaCX\Bundle\IbexaImportExport\Writer\WriterRegistry;
use Ibexa\Contracts\AdminUi\Controller\Controller;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\Response;

class WriterController extends Controller
{
    protected Filesystem $filesystem;
    protected WriterRegistry $writerRegistry;

    public function __construct(Filesystem $filesystem, WriterRegistry $writerRegistry)
    {
        $this->filesystem = $filesystem;
        $this->writerRegistry = $writerRegistry;
    }

    public function downloadFileAction(Job $job, $writerIndex)
    {
//        $writer = $this->writerRegistry->get($job->getWriterResults()[$writerIndex]['writerIdentifier']);
//        if (!$writer instanceof AbstractStreamWriter) {
//            exit();
//        }

//        $filepath = $job->getWriterResults()[$writerIndex]['filePath'];

        return new Response('');
    }
}
