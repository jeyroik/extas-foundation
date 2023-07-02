<?php
namespace extas\components\commands;

use extas\components\crawlers\CrawlerEntities;
use extas\components\crawlers\CrawlerStorage;
use extas\components\installers\InstallerEntities;
use extas\components\installers\InstallerStorage;
use extas\interfaces\commands\IHaveConfigOptions;
use extas\interfaces\commands\IHaveEntitiesOptions;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class InstallCommand extends Command implements IHaveConfigOptions, IHaveEntitiesOptions
{
    use THasConfigOptions;
    use THasEntitiesOptions;

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

        $this->attachConfigOptions();
        $this->attachEntitiesOptions();
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
        $this->shoutList(array_column($packagesStorages, 'name'), $output);
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

    protected function shoutList(array $list, OutputInterface $output): void
    {
        foreach ($list as $item) {
            $output->writeln([' - ' . $item]);
        }
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
        $crawler = new CrawlerStorage(
            $input->getOption(static::OPTION__CFG_APP_FILENAME),
            $input->getOption(static::OPTION__CFG_PCKGS_FILENAME)
        );
        return $crawler($input->getOption(static::OPTION__PATH_PACKAGES));
    }

    protected function getEntitiesConfigs(InputInterface $input): array
    {
        $crawler = new CrawlerEntities(
            $input->getOption(static::OPTION__ENTITY_APP_FILENAME),
            $input->getOption(static::OPTION__ENTITY_PCKGS_FILENAME)
        );
        return $crawler($input->getOption(static::OPTION__PATH_PACKAGES));
    }
}
