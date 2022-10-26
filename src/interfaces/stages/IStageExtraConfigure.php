<?php
namespace extas\interfaces\stages;

use Symfony\Component\Console\Command\Command;

interface IStageExtraConfigure
{
    public const NAME = 'extas.extra.configure';

    public function __invoke(Command &$command);
}
