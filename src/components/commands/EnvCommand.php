<?php
namespace extas\components\commands;

use extas\components\crawlers\CrawlerStorage;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class EnvCommand extends Command
{
    public const FIELD__ENVS = 'envs';

    /**
     * Configure the current command.
     */
    protected function configure()
    {
        $this
            ->setName('env')
            ->setAliases(['e'])
            ->setDescription('Show required envs.')
            ->setHelp('This command allows you to see all required envs.')
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
        $c = new CrawlerStorage();
        list($app, $packages) = $c(getcwd());

        $packages[] = $app;

        foreach ($packages as $p) {
            $this->printEnvs($p, $output);
            $output->writeln(['']);
        }

        return 0;
    }

    protected function printEnvs(array $config, OutputInterface $output): void
    {
        $envs = $config[static::FIELD__ENVS] ?? [];
        $name = $config['name'] ?? 'Unknown package (missed "name")';

        $output->writeln(['Package ' . $name . ': ']);

        foreach ($envs as $name => $description) {
            $output->writeln(['<info>'.$name . '</info>: ' . $description]);
        }
    }
}
