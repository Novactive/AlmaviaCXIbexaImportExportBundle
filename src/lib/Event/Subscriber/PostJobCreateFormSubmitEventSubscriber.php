<?php

declare(strict_types=1);

namespace AlmaviaCX\Bundle\IbexaImportExport\Event\Subscriber;

use AlmaviaCX\Bundle\IbexaImportExport\Event\PostJobCreateFormSubmitEvent;
use AlmaviaCX\Bundle\IbexaImportExport\File\FileHandler;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class PostJobCreateFormSubmitEventSubscriber implements EventSubscriberInterface
{
    protected FileHandler $fileHandler;

    public function __construct(
        FileHandler $fileHandler
    ) {
        $this->fileHandler = $fileHandler;
    }

    public static function getSubscribedEvents()
    {
        return [
            PostJobCreateFormSubmitEvent::class => ['onPostJobCreateFormSubmit', 0],
        ];
    }

    public function onPostJobCreateFormSubmit(PostJobCreateFormSubmitEvent $event): void
    {
//        $job = $event->getJob();

//        $options = $job->getOptions()->getReader();
//        if($options instanceof CsvReaderOptions && $options->getFile() instanceof UploadedFile) {
//            $file = $options->getFile();
//            $fileHandler = fopen($file->getPathname(), 'rb');
//            $newFilename = sprintf('job/csv_reader_%s.%s',
        // \Ramsey\Uuid\Uuid::uuid4()->toString(),
        // $file->getClientOriginalExtension());
//            $this->fileHandler->writeStream($newFilename,  $fileHandler, new Config());
//            $options->setFile($newFilename);
//        }
    }
}
