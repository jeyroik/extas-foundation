<?php
namespace extas\components\installers;

use extas\components\exceptions\AlreadyExist;
use extas\components\exceptions\MissedOrUnknown;
use extas\components\extensions\Extension;
use extas\components\plugins\Plugin;
use extas\components\repositories\RepositoryBuilder;
use extas\components\SystemContainer;
use extas\interfaces\repositories\IRepository;

class InstallerStorage
{
    public const FIELD__DRIVERS = 'drivers';
    public const FIELD__TABLES = 'tables';

    protected const TABLE__PLUGINS = 'plugins';
    protected const TABLE__EXTENSIONS = 'extensions';

    protected array $app = [];
    protected array $packages = [];

    public function __construct(array $appStorage, array $packagesStorages)
    {
        $this->app = $appStorage;
        $this->packages = $packagesStorages;
    }

    public function install(string $pathSave, string $pathTemplate): void
    {
        $drivers = $this->app[static::FIELD__DRIVERS] ?? [];

        if (empty($drivers)) {
            throw new MissedOrUnknown('storage drivers');
        }

        $tables = $this->merge(static::FIELD__TABLES);
        $builder = new RepositoryBuilder([
            RepositoryBuilder::FIELD__PATH_SAVE => $pathSave,
            RepositoryBuilder::FIELD__PATH_TEMPLATE => $pathTemplate
        ]);

        foreach ($drivers as $driver) {
            $builderConfig = $driver;
            $builderConfig[static::FIELD__TABLES] = [];

            foreach ($driver[static::FIELD__TABLES] as $tableName) {
                if (isset($tables[$tableName])) {
                    $builderConfig[static::FIELD__TABLES][$tableName] = $tables[$tableName];
                    unset($tables[$tableName]);
                } else {
                    //notify "Missed $tableName table configuration"
                }
            }

            $builder->build($builderConfig);
        }

        $this->installItems(static::TABLE__PLUGINS, Plugin::class);
        $this->installItems(static::TABLE__EXTENSIONS, Extension::class);
    }

    protected function merge(string $field): array
    {
        $result = $this->app[static::FIELD__TABLES] ?? [];

        foreach ($this->packages as $p) {
            $result = array_merge($result, $p[$field] ?? []);
        }

        return $result;
    }

    protected function installItems(string $tableName, string $itemClass): void
    {
        /**
         * @var IRepository $repo
         */
        $repo = SystemContainer::getItem($tableName);
        $items = $this->app[$tableName] ?? [];

        foreach ($items as $item) {
            try{
                $repo->create(new $itemClass($item));
            } catch (AlreadyExist $e) {
                continue;
            }
        }

        $items = $this->merge($tableName);

        foreach ($items as $item) {
            try {
                $repo->create(new $itemClass($item));
            } catch (AlreadyExist $e) {
                continue;
            }
        }
    }
}
