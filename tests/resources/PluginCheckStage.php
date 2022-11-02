<?php
namespace tests\resources;

use extas\components\plugins\Plugin;
use extas\interfaces\stages\IStageBeforeInstallEntity;

class PluginCheckStage extends Plugin implements IStageBeforeInstallEntity
{
    public function __invoke(array &$entity): void
    {
        $entity['changed'] = __METHOD__;
    }
}
