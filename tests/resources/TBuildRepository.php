<?php
namespace tests\resources;

use extas\components\Item;
use extas\components\repositories\RepositoryBuilder;
use extas\interfaces\repositories\IRepository;

trait TBuildRepository
{
    protected string $templatesPath = '';
    protected string $extasDriver = '\\extas\\components\\repositories\\drivers\\DriverFileJson';
    protected array $extasDriverOptions = [
        "path" => "tests/tmp/",
        "db" => "system"
    ];

    protected function installLibItems(string $libName, string $testDir = '', string $extasExt = 'php'): void
    {
        $app = new class extends Item {
            protected function getSubjectForExtension(): string
            {
                return '';
            }
        };

        $extasPath = $testDir . '/../../vendor/' . $libName . '/extas.' . $extasExt;

        $extas = $extasExt == 'php' ? include $extasPath : json_decode(file_get_contents($extasPath), true);

        foreach ($extas as $tableName => $items) {
            if (!is_array($items)) {
                continue;
            }
            
            foreach ($items as $item) {
                $itemClass = $app->$tableName()->getItemClass();
                $app->$tableName(new $itemClass($item));
            }
        }
    }

    protected function buildLibsRepos(string $libName, string $testDir = '', string $storageExt = 'php'): array
    {
        $templatesPath = $testDir . '/../../vendor/jeyroik/extas-foundation/resources/';
        $storagePath = $testDir . '/../../vendor/' . $libName . '/extas.storage.' . $storageExt;

        $storage = $storageExt == 'php' ? include $storagePath : json_decode(file_get_contents($storagePath), true);
        $tables = $storage['tables'];
        $names = [];

        foreach ($tables as $name => $options) {
            $options['namespace'] = 'tests\\tmp';
            $tables[$name] = $options;
            $names[] = $name;
        }

        $this->buildRepo($templatesPath, $tables);

        return $names;
    }

    protected function buildRepo(string $templatesPath, array $tables): void
    {
        $builder = new RepositoryBuilder([
            RepositoryBuilder::FIELD__PATH_SAVE => getcwd() . '/tests/tmp',
            RepositoryBuilder::FIELD__PATH_TEMPLATE => $templatesPath
        ]);

        $builder->build([
            "class" => $this->extasDriver,
            "options" => $this->extasDriverOptions,
            "tables" => $tables
        ]);
    }

    protected function deleteRepo(string $alias): void
    {
        unlink(getcwd() . '/tests/tmp/Repository' . ucfirst($alias) . '.php');
    }

    protected function dropDatabase(string $basePath = __DIR__): void
    {
        unlink($basePath . '/../tmp/system');
    }

    protected function buildPluginsRepo(): IRepository
    {
        $this->buildRepo($this->templatesPath ?: __DIR__ . '/../../resources', [
            "plugins" => [
                'namespace' => 'tests\\tmp',
                'item_class' => '\\extas\\components\\plugins\\Plugin',
                'pk' => 'id',
                'code' => [
                    'drop-after' => '\\extas\\components\\Plugins::reset();'
                ]
            ]
        ]);

        return new \tests\tmp\RepositoryPlugins();
    }

    protected function removePluginsRepo(): void
    {
        unlink(getcwd() . '/tests/tmp/RepositoryPlugins.php');
    }

    protected function buildExtensionsRepo(): IRepository
    {
        $this->buildRepo(__DIR__ . '/../../resources', [
            "extensions" => [
                "namespace" => "tests\\tmp",
                "item_class"=> "\\extas\\components\\extensions\\Extension",
                "pk"=> "name"
            ]
        ]);

        return new \tests\tmp\RepositoryExtensions();
    }

    protected function removePExtenionsRepo(): void
    {
        unlink(getcwd() . '/tests/tmp/RepositoryExtensions.php');
    }
}
