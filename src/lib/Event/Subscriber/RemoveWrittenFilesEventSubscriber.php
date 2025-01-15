<?php

declare(strict_types=1);

namespace AlmaviaCX\Bundle\IbexaImportExport\Event\Subscriber;

use AlmaviaCX\Bundle\IbexaImportExport\Event\ResetJobRunEvent;
use AlmaviaCX\Bundle\IbexaImportExport\File\FileHandler;
use AlmaviaCX\Bundle\IbexaImportExport\Writer\Csv\CsvWriter;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class RemoveWrittenFilesEventSubscriber implements EventSubscriberInterface
{
    public function __construct(
        protected FileHandler $fileHandler
    ) {
    }

    public static function getSubscribedEvents(): array
    {
        return [
            ResetJobRunEvent::class => ['onResetJob', 0],
        ];
    }

    /**
     * @throws \League\Flysystem\FilesystemException
     */
    public function onResetJob(ResetJobRunEvent $event): void
    {
        $job = $event->getExecution();
        $results = $job->getWriterResults();

        foreach ($results as $result) {
            if (CsvWriter::class === $result->getWriterType() && isset($result->getResults()['filepath'])) {
                $this->fileHandler->delete($result->getResults()['filepath']);
            }
        }
    }
}
