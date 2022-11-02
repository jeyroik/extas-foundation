<?php
namespace extas\components\commands;

use extas\components\crawlers\CrawlerStorage;
use extas\interfaces\commands\IHaveConfigOptions;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class EnvCommand extends Command implements IHaveConfigOptions
{
    use THasConfigOptions;

    public const FIELD__ENVS = 'envs';
    public const FIELD__DESCRIPTION = 'description';
    public const FIELD__DEFAULT = 'default';

    public const OPTION__GENERATE = 'generate';
    public const OPTION__GENERATE_PATH = 'generate_path';


    public const FLAG__DEFAULT_VALUES = 'd';
    public const FLAG__CLEAN = 'c';

    protected string $envContent = '';

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
            ->addOption(
                static::OPTION__GENERATE,
                'g',
                InputOption::VALUE_OPTIONAL,
                'Generate .env with or not default values. 0 - do not generate, ' .
                    static::FLAG__DEFAULT_VALUES . ' - with default values, ' . 
                    static::FLAG__CLEAN . ' - clean .env',
                0
            )
            ->addOption(
                static::OPTION__GENERATE_PATH,
                'p',
                InputOption::VALUE_OPTIONAL,
                'Path to save generated .env',
                getcwd()
            )
        ;

        $this->attachConfigOptions();
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
        $gen = $input->getOption(static::OPTION__GENERATE);

        $c = new CrawlerStorage(
            $input->getOption(static::OPTION__CFG_APP_FILENAME),
            $input->getOption(static::OPTION__CFG_PCKGS_FILENAME)
        );
        list($app, $packages) = $c(getcwd());

        $packages[] = $app;

        foreach ($packages as $p) {
            $this->printEnvs($p, $output);
            $output->writeln(['']);
        }

        if ($gen) {
            $output->writeln(['Begin .env generating...']);
            foreach ($packages as $p) {
                $this->generateEnv($gen, $p, $output);
                $output->writeln(['']);
            }
            file_put_contents($input->getOption(static::OPTION__GENERATE_PATH) . '/.env', $this->envContent);
            $output->writeln(['Generation done. Check '.$input->getOption(static::OPTION__GENERATE_PATH).'/.env']);
        }

        return 0;
    }

    protected function printEnvs(array $config, OutputInterface $output): void
    {
        $envs = $config[static::FIELD__ENVS] ?? [];
        $name = $config['name'] ?? 'Unknown package (missed "name")';

        $output->writeln(['Package ' . $name . ': ']);

        foreach ($envs as $name => $description) {
            $output->writeln(['<info>'.$name . '</info>: ' . $this->getEnvDescription($description)]);
        }
    }

    protected function getEnvDescription($description): string
    {
        if (is_string($description)) {
            return $description;
        }

        return ($description[static::FIELD__DESCRIPTION] ?? '') . 
            ' Default: ' .  ($description[static::FIELD__DEFAULT] ?? '');
    }

    protected function generateEnv(string $flag, array $config, OutputInterface $output)
    {
        if (empty($config)) {
            return false;
        }

        $envs = $config[static::FIELD__ENVS] ?? [];
        $name = $config['name'] ?? 'Unknown package (missed "name")';

        $output->writeln(['Adding envs from ' . $name . '...']);

        $this->envContent .= '# ' . $name . "\r\n";

        foreach ($envs as $name => $description) {
            $value = $flag == static::FLAG__DEFAULT_VALUES ? $this->getEnvDefault($description) : '';
            $this->envContent .= $name . ' = ' . $value . "\r\n";
        }
    }

    protected function getEnvDefault($config): string
    {
        if (is_string($config)) {
            return '';
        }

        return $config[static::FIELD__DEFAULT] ?? '';
    }
}
