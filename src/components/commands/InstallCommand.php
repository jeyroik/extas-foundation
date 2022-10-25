<?php
namespace extas\components\commands;

use extas\components\crawlers\CrawlerEntities;
use extas\components\crawlers\CrawlerStorage;
use extas\components\installers\InstallerEntities;
use extas\components\installers\InstallerStorage;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class InstallCommand extends Command
{
    public const OPTION__PATH_TEMPLATES = 'path_templates';
    public const OPTION__PATH_SAVE = 'path_save';

    /**
     * Configure the current command.
     */
    protected function configure()
    {
        $this
            ->setName('install')
            ->setAliases(['i'])
            ->setDescription('Install entities using extas-compatible package file.')
            ->setHelp('This command allows you to install entities using extas-compatible package file.')
            ->addOption(
                static::OPTION__PATH_TEMPLATES,
                't',
                InputOption::VALUE_OPTIONAL,
                'Repository templates directory absolute path',
                getcwd() . '/resources'
            )
            ->addOption(
                static::OPTION__PATH_SAVE,
                's',
                InputOption::VALUE_OPTIONAL,
                'Generated repositories classes save directory absolute path',
                getcwd() . '/extas_build'
            )
        ;
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     *
     * @return int|mixed
     * @throws
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $pathSave = $input->getOption(static::OPTION__PATH_SAVE);
        $pathTemplates = $input->getOption(static::OPTION__PATH_TEMPLATES);

        // Install storages with plugins and extensions
        list($appStorage, $packagesStorages) = $this->getStorageConfigs();
        $storageInstaller = new InstallerStorage($appStorage, $packagesStorages);
        $storageInstaller->install($pathSave, $pathTemplates);

        // Install other entities
        list($appEntities, $packagesEntities) = $this->getEntitiesConfigs();
        $entitiesInstaller = new InstallerEntities($appEntities, $packagesEntities);
        $entitiesInstaller->install();
    }

    protected function getStorageConfigs(): array
    {
        $crawler = new CrawlerStorage();
        return $crawler(getcwd());
    }

    protected function getEntitiesConfigs(): array
    {
        $crawler = new CrawlerEntities();
        return $crawler(getcwd());
    }
}
