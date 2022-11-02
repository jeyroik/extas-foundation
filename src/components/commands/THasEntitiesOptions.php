<?php
namespace extas\components\commands;

use extas\components\crawlers\CrawlerEntities;
use extas\interfaces\commands\IHaveEntitiesOptions;
use Symfony\Component\Console\Input\InputOption;

trait THasEntitiesOptions
{
    public function attachEntitiesOptions(): void
    {
        $this->addOption(
            IHaveEntitiesOptions::OPTION__ENTITY_APP_FILENAME,
            '',
            InputOption::VALUE_OPTIONAL,
            'Application configuration filename',
            CrawlerEntities::FILENAME__APP
        )
        ->addOption(
            IHaveEntitiesOptions::OPTION__ENTITY_PCKGS_FILENAME,
            '',
            InputOption::VALUE_OPTIONAL,
            'Package configuration filename',
            CrawlerEntities::FILENAME__PACKAGES
        );
    }
}
