<?php
namespace tests\resources;

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

    protected function dropDatabase(): void
    {
        unlink(__DIR__ . '/../tmp/system');
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
