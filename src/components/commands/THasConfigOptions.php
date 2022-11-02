<?php
namespace extas\components\commands;

use extas\components\crawlers\CrawlerStorage;
use extas\interfaces\commands\IHaveConfigOptions;
use Symfony\Component\Console\Input\InputOption;

trait THasConfigOptions
{
    public function attachConfigOptions(): void
    {
        $this->addOption(
            IHaveConfigOptions::OPTION__CFG_APP_FILENAME,
            '',
            InputOption::VALUE_OPTIONAL,
            'Application configuration filename',
            CrawlerStorage::FILENAME__APP
        )
        ->addOption(
            IHaveConfigOptions::OPTION__CFG_PCKGS_FILENAME,
            '',
            InputOption::VALUE_OPTIONAL,
            'Package configuration filename',
            CrawlerStorage::FILENAME__PACKAGES
        );
    }
}
