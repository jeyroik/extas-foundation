<?php
namespace extas\components\installers;

use extas\components\exceptions\AlreadyExist;
use extas\components\Plugins;
use extas\components\SystemContainer;
use extas\components\THasOutput;
use extas\interfaces\IHaveOutput;
use extas\interfaces\repositories\IRepository;
use extas\interfaces\stages\IStageBeforeInstallEntity;
use extas\interfaces\stages\IStageIsToInstallEntity;

class InstallerEntities implements IHaveOutput
{
    use THasOutput;

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
        $this->installPackage($this->app);

        foreach ($this->packages as $package) {
            $this->installPackage($package);
        }
    }

    protected function installPackage($package): void
    {
        foreach ($package as $tableName => $entities) {
            if ($this->isNeedToSkip($tableName, $entities)) {
                continue;
            }
            $this->installTable($tableName, $entities);
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
            $isToInstall = true;

            foreach (Plugins::byStage(IStageBeforeInstallEntity::NAME) as $plugin) {
                /**
                 * @var IStageBeforeInstallEntity $plugin
                 */
                $plugin($entity);
            }

            foreach (Plugins::byStage(IStageIsToInstallEntity::NAME) as $plugin) {
                /**
                 * @var IStageIsToInstallEntity $plugin
                 */
                $isToInstall = $plugin($repo, $entity);
            }

            try {
                $isToInstall && $repo->create(new $itemClass($entity));
            } catch (AlreadyExist $e) {
                continue;
            }
        }

        $this->appendOutput($repo->getOutput(), $tableName . '_repo');
    }
}
