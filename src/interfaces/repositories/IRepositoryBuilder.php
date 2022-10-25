<?php
namespace extas\interfaces\repositories;

use extas\interfaces\IHaveConfig;

interface IRepositoryBuilder extends IHaveConfig
{
    public const FIELD__PATH_TEMPLATE = 'pathTemplate';
    public const FIELD__PATH_SAVE = 'pathSave';

    public function build(array $driverConfig): void;

    public function getPathTemplate(): string;
    public function getPathSave(): string;
}
