<?php
namespace extas\components\commands;

use extas\components\crawlers\CrawlerPHP;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class GenerateCommand extends Command
{
    public const OPTION__GENERATE_PATTERN = 'pattern';
    public const OPTION__GENERATE_PATH = 'generate_path';
    public const OPTION__SEARCH_PATH = 'search_path';

    /**
     * Configure the current command.
     */
    protected function configure()
    {
        $this
            ->setName('generate')
            ->setAliases(['g'])
            ->setDescription('Generate extas[.app][.storage].json from php file.')
            ->setHelp('This command allows you generate extas json config from php file.')
            ->addOption(
                static::OPTION__GENERATE_PATH, 'p', InputOption::VALUE_OPTIONAL,
                'Path to save generated configs',
                getcwd() . '/extas-build'
            )
            ->addOption(
                static::OPTION__SEARCH_PATH, 's', InputOption::VALUE_OPTIONAL,
                'Path to search configs',
                getcwd()
            )
            ->addOption(
                static::OPTION__GENERATE_PATTERN, 'r', InputOption::VALUE_OPTIONAL,
                'Pattern for configs. Result files will have the same name with json extension.',
                'extas*'
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
        $crawler = new CrawlerPHP(
            $input->getOption(static::OPTION__GENERATE_PATTERN),
            $input->getOption(static::OPTION__SEARCH_PATH)
        );

        $crawler->run($input->getOption(static::OPTION__GENERATE_PATH));

        $output->writeln(['Generation done']);

        return 0;
    }
}
