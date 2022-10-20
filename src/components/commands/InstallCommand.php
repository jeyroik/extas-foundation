<?php
namespace extas\components\commands;

use extas\components\crawlers\CrawlerExtas;
use extas\components\repositories\RepositoryBuilder;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class InstallCommand extends Command
{
    public const OPTION__TEMPLATES_PATH = 'repo_templates_path';
    public const BUILD__PATH = '/extas_build/';

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
                static::OPTION__TEMPLATES_PATH,
                't',
                InputOption::VALUE_OPTIONAL,
                'Repository templates directory absolute path',
                getcwd() . '/resources'
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
        $crawler = new CrawlerExtas();
        list($appConfig, $packages) = $crawler(getcwd());

        $templatesPath = $input->getOption(static::OPTION__TEMPLATES_PATH);

        $this->installPackage($appConfig, $templatesPath);

        foreach ($packages as $package) {
            $this->installPackage($package, $templatesPath);
        }
    }

    protected function installPackage(array $package)
    {
        $this->installStorage($package['storage']);
        // installPlugins($package['plugins']);
        // installExtensions($package['extensions']);
        // installEntities($package['entities]);
    }

    protected function installStorage(array $storageConfig): void
    {
        $builder = new RepositoryBuilder(getcwd() . static::BUILD__PATH, '');

        foreach ($storageConfig as $driverConfig) {
            $builder->build($driverConfig);
        }
    }

    protected function installPlugins(array $plugins): void
    {
        foreach ($plugins as $plugin) {
            Repositories::get('plugins')->create($plugin);
        }
    }

    protected function installExtensions(array $extensions): void
    {
        foreach ($extensions as $extension) {
            Repositories::get('extensions')->create($extension);
        }
    }

    protected function installEntities(array $entities): void
    {
        foreach ($entities as $entity) {
            
        }
    }
}
