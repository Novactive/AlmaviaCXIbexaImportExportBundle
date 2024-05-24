<?php

declare(strict_types=1);

namespace AlmaviaCX\Bundle\IbexaImportExportBundle\Controller\Admin;

use AlmaviaCX\Bundle\IbexaImportExport\Component\ComponentRegistry;
use AlmaviaCX\Bundle\IbexaImportExport\Job\Job;
use Ibexa\Contracts\AdminUi\Controller\Controller;
use Ibexa\Core\Base\Exceptions\NotFoundException;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\Response;
use Twig\Environment;
use Twig\Error\LoaderError;

class WriterController extends Controller
{
    protected Filesystem $filesystem;
    protected Environment $twig;
    protected ComponentRegistry $componentRegistry;

    public function __construct(Filesystem $filesystem, Environment $twig, ComponentRegistry $componentRegistry)
    {
        $this->filesystem = $filesystem;
        $this->twig = $twig;
        $this->componentRegistry = $componentRegistry;
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
//        $writer = $this->writerRegistry->get($job->getWriterResults()[$writerIndex]['writerIdentifier']);
//        if (!$writer instanceof AbstractStreamWriter) {
//            exit();
//        }

//        $filepath = $job->getWriterResults()[$writerIndex]['filePath'];

        return new Response('');
    }
}
