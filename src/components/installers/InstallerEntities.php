<?php
namespace extas\components\installers;

use extas\components\Plugins;
use extas\components\SystemContainer;
use extas\interfaces\repositories\IRepository;
use extas\interfaces\stages\IStageBeforeInstallEntity;

class InstallerEntities
{
    protected const FIELD__NAME = 'name';

    protected array $app = [];
    protected array $packages = [];

    public function __construct(array $app, array $packages)
    {
        $this->app = $app;
        $this->packages = $packages;
    }

    public function install(): void
    {
        foreach ($this->app as $tableName => $entities) {
            if ($this->isNeedToSkip($tableName, $entities)) {
                continue;
            }
            $this->installTable($tableName, $entities);
        }

        foreach ($this->packages as $name => $package) {
            foreach ($package as $tableName => $entities) {
                if ($this->isNeedToSkip($tableName, $entities)) {
                    continue;
                }
                $this->installTable($tableName, $entities);
            }
        }
    }

    protected function isNeedToSkip(string $tableName, $entities): bool
    {
        if ($tableName == static::FIELD__NAME) {
            return true;
        }

        if (!is_array($entities)) {
            return true;
        }

        return false;
    }

    protected function installTable(string $tableName, array $entities): void
    {
        /**
         * @var IRepository $repo
         */
        $repo = SystemContainer::getItem($tableName);
        $itemClass = $repo->getItemClass();

        foreach ($entities as $entity) {
            foreach (Plugins::byStage(IStageBeforeInstallEntity::NAME) as $plugin) {
                /**
                 * @var IStageBeforeInstallEntity $plugin
                 */
                $plugin($entity);
            }
            $repo->create(new $itemClass($entity));
        }
    }
}
