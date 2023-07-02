<?php
namespace extas\interfaces\repositories;

use extas\interfaces\IHaveConfig;
use extas\interfaces\IHaveOutput;

interface IRepositoryBuilder extends IHaveConfig, IHaveOutput
{
    public const FIELD__PATH_TEMPLATE = 'pathTemplate';
    public const FIELD__PATH_SAVE = 'pathSave';

    public function build(array $driverConfig): void;

    public function getPathTemplate(): string;
    public function getPathSave(): string;
}
