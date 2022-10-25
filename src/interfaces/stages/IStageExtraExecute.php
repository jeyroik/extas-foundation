<?php
namespace extas\interfaces\stages;

use Symfony\Component\Console\Input\Input;
use Symfony\Component\Console\Output\Output;

interface IStageExtraExecute
{
    public const NAME =  'extas.extra.execute';

    public function __invoke(Input $input, Output $output);
}
