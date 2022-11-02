<?php
namespace tests\resources;

use extas\components\plugins\Plugin;
use extas\interfaces\repositories\IRepository;
use extas\interfaces\stages\IStageIsToInstallEntity;

class PluginIsInstall extends Plugin implements IStageIsToInstallEntity
{
    public static $count = 0;

    public function __invoke(IRepository $repo, array &$entity): bool
    {
        self::$count++;

        return true;
    }
}
