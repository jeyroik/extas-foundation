<?php
namespace tests\resources;

use extas\components\plugins\Plugin;
use extas\interfaces\stages\IStageExtraConfigure;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputOption;

class PluginExtraConfigure extends Plugin implements IStageExtraConfigure
{
    public function __invoke(Command &$command)
    {
        $command->addOption('test', 't', InputOption::VALUE_OPTIONAL, 'test', 'default-value');
    }
}
