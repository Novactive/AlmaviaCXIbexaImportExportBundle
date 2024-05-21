<?php

declare(strict_types=1);

namespace AlmaviaCX\Bundle\IbexaImportExport\Event\Subscriber;

use AlmaviaCX\Bundle\IbexaImportExport\Event\PreJobRunEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Filesystem\Filesystem;

class RemoveWrittenFilesEventSubscriber implements EventSubscriberInterface
{
    protected Filesystem $filesystem;

    public function __construct(Filesystem $filesystem)
    {
        $this->filesystem = $filesystem;
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
            if (isset($result['filepath'])) {
                $this->filesystem->remove($result['filepath']);
            }
        }
    }
}
