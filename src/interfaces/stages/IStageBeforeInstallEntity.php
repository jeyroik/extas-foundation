<?php
namespace extas\interfaces\stages;

interface IStageBeforeInstallEntity
{
    public const NAME = 'extas.before.install.entity';

    public function __invoke(array &$entity);
}
