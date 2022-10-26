<?php
namespace tests\resources;

use extas\components\plugins\Plugin;
use extas\interfaces\stages\IStageExtraExecute;
use Symfony\Component\Console\Input\Input;
use Symfony\Component\Console\Output\Output;

class PluginExtraExecute extends Plugin implements IStageExtraExecute
{
    public function __invoke(Input $input, Output $output)
    {
        $output->writeln(['Got test parameter with value = ' . $input->getOption('test')]);
    }
}
