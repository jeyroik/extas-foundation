<?php
namespace extas\interfaces\stages;

use extas\interfaces\repositories\IRepository;

interface IStageIsToInstallEntity
{
    public const NAME = 'extas.is.install.entity';

    public function __invoke(IRepository $repo, array &$entity): bool;
}
