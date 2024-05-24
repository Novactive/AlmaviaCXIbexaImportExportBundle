<?php

declare(strict_types=1);

namespace AlmaviaCX\Bundle\IbexaImportExport\AdminUi\Menu\Event;

use Ibexa\AdminUi\Menu\MainMenuBuilder;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Ibexa\AdminUi\Menu\Event\ConfigureMenuEvent;

class MenuListener implements EventSubscriberInterface
{
    public static function getSubscribedEvents(): array
    {
        return [
            ConfigureMenuEvent::MAIN_MENU => ['onMenuConfigure', 0],
        ];
    }

    public function onMenuConfigure(ConfigureMenuEvent $event): void
    {
        $menu = $event->getMenu();

        $menu[MainMenuBuilder::ITEM_CONTENT]->addChild(
            'export_import_job_list',
            [
                'label' => 'export_import_job_list',
                'route' => 'import_export.job.list',
            ]
        );
    }
}

