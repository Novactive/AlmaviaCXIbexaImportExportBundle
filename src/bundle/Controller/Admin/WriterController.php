<?php

declare(strict_types=1);

namespace AlmaviaCX\Bundle\IbexaImportExportBundle\Controller\Admin;

use AlmaviaCX\Bundle\IbexaImportExport\Component\ComponentRegistry;
use AlmaviaCX\Bundle\IbexaImportExport\File\DownloadFileResponse;
use AlmaviaCX\Bundle\IbexaImportExport\File\FileHandler;
use AlmaviaCX\Bundle\IbexaImportExport\Job\Job;
use AlmaviaCX\Bundle\IbexaImportExport\Writer\Stream\AbstractStreamWriter;
use Ibexa\Contracts\AdminUi\Controller\Controller;
use Ibexa\Core\Base\Exceptions\NotFoundException;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\Response;
use Twig\Environment;
use Twig\Error\LoaderError;

class WriterController extends Controller
{
    protected Environment $twig;
    protected ComponentRegistry $componentRegistry;
    protected FileHandler $fileHandler;

    /**
     * @param \Twig\Environment                                               $twig
     * @param \AlmaviaCX\Bundle\IbexaImportExport\Component\ComponentRegistry $componentRegistry
     * @param \AlmaviaCX\Bundle\IbexaImportExport\File\FileHandler            $fileHandler
     */
    public function __construct( Environment $twig, ComponentRegistry $componentRegistry, FileHandler $fileHandler )
    {
        $this->twig = $twig;
        $this->componentRegistry = $componentRegistry;
        $this->fileHandler = $fileHandler;
    }


    public function displayResults(Job $job): Response
    {
        $results = [];
        foreach ($job->getWriterResults() as $index => $writerResults) {
            try {
                $writer = $this->componentRegistry->getComponent($writerResults->getType());

                $template = sprintf(
                    '@ibexadesign/import_export/writer/results/%s.html.twig',
                    str_replace('.', '_', $writer->getIdentifier())
                );
                $this->twig->load($template);

                $results[] = [
                    'template' => $template,
                    'parameters' => [
                        'results' => $writerResults->getResults(),
                        'writerIndex' => $index,
                        'writer' => $writer,
                        'job' => $job,
                    ],
                ];
            } catch (LoaderError|NotFoundException $e) {
                throw $e;
            }
        }

        return $this->render('@ibexadesign/import_export/job/results.html.twig', [
            'job' => $job,
            'results' => $results,
        ]);
    }

    public function downloadFile(Job $job, $writerIndex)
    {
        $writerResults = $job->getWriterResults()[$writerIndex];
        $writer = $this->componentRegistry->getComponent($writerResults->getType());
        if (!$writer instanceof AbstractStreamWriter) {
            exit();
        }

        return new DownloadFileResponse($writerResults->getResults()['filepath'], $this->fileHandler);
    }
}
