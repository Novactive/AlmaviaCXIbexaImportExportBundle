<?php

declare(strict_types=1);

namespace AlmaviaCX\Bundle\IbexaImportExport\Event\Subscriber;

use AlmaviaCX\Bundle\IbexaImportExport\Event\PreJobRunEvent;
use AlmaviaCX\Bundle\IbexaImportExport\File\FileHandler;
use AlmaviaCX\Bundle\IbexaImportExport\Writer\Csv\CsvWriter;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class RemoveWrittenFilesEventSubscriber implements EventSubscriberInterface
{
    protected FileHandler $fileHandler;

    public function __construct(FileHandler $fileHandler)
    {
        $this->fileHandler = $fileHandler;
    }

    public static function getSubscribedEvents()
    {
        return [
            PreJobRunEvent::class => ['onPreJobRun', 0],
        ];
    }

    public function onPreJobRun(PreJobRunEvent $event)
    {
        $results = $event->getJob()->getWriterResults();

        foreach ($results as $result) {
            if (CsvWriter::class === $result->getType() && isset($result->getResults()['filepath'])) {
                $this->fileHandler->delete($result->getResults()['filepath']);
            }
        }
    }
}
