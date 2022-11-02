<?php
namespace extas\interfaces\commands;

use Symfony\Component\Console\Command\Command;

interface IHaveConfigOptions
{
    public const OPTION__CFG_APP_FILENAME = 'cfg_app_path';
    public const OPTION__CFG_PCKGS_FILENAME = 'cfg_pckgs_path';

    public function attachConfigOptions(): void;
}
