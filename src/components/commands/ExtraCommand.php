<?php
namespace extas\components\commands;

use extas\components\Plugins;
use extas\interfaces\stages\IStageExtraConfigure;
use extas\interfaces\stages\IStageExtraExecute;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ExtraCommand extends Command
{
    /**
     * Configure the current command.
     */
    protected function configure()
    {
        $this
            ->setName('extra')
            ->setAliases(['e'])
            ->setDescription('Install extra entities.')
            ->setHelp('This command allows you to install extra entities.');

        foreach (Plugins::byStage(IStageExtraConfigure::NAME) as $plugin) {
            /**
             * @var IStageExtraConfigure $plugin
             */
            $plugin ($this);
        }
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
        foreach (Plugins::byStage(IStageExtraExecute::NAME) as $plugin) {
            /**
             * @var IStageExtraExecute $plugin
             */
            $plugin ($input, $output);
        }
    }
}
