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
    public const OPTION__PATH_PACKAGES = 'path_packages';

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
            ->addOption(
                static::OPTION__PATH_PACKAGES,
                'p',
                InputOption::VALUE_OPTIONAL,
                'Path to search packages for',
                getcwd()
            );
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
        $output->writeln(['Extas Foundation v6 Installer']);

        $start = time();

        $pathSave = $input->getOption(static::OPTION__PATH_SAVE);
        $pathTemplates = $input->getOption(static::OPTION__PATH_TEMPLATES);

        $output->writeln(['Collecting storage configurations...']);
        list($appStorage, $packagesStorages) = $this->getStorageConfigs($input);

        $this->shout(true, count($packagesStorages), 'configurations', $output);
        $storageInstaller = new InstallerStorage($appStorage, $packagesStorages);
        $storageInstaller->install($pathSave, $pathTemplates);

        $output->writeln(['Done.', 'Collecting entities configurations...']);
        list($appEntities, $packagesEntities) = $this->getEntitiesConfigs($input);

        $this->shout(!empty($appEntities), count($packagesEntities), 'entities', $output);
        $entitiesInstaller = new InstallerEntities($appEntities, $packagesEntities);
        $entitiesInstaller->install();

        $end = time();
        $output->writeln(['Done.', '', 'Installation finished in ' . ($end-$start) . 's.']);

        return 0;
    }

    protected function shout(bool $isApp, int $packagesCount, string $subject = 'entities', OutputInterface $output): void
    {
        $app = $isApp ? "app $subject + " : '';

        $output->writeln([
            'Done.',
            'Found ' . $app . $packagesCount . ' package(s) ' . $subject . '.',
            'Installing ' . $subject . '...'
        ]);
    }

    protected function getStorageConfigs(InputInterface $input): array
    {
        $crawler = new CrawlerStorage();
        return $crawler($input->getOption(static::OPTION__PATH_PACKAGES));
    }

    protected function getEntitiesConfigs(InputInterface $input): array
    {
        $crawler = new CrawlerEntities();
        return $crawler($input->getOption(static::OPTION__PATH_PACKAGES));
    }
}
