<?php
namespace tests\resources;

use extas\components\repositories\RepositoryBuilder;
use extas\interfaces\repositories\IRepository;

trait TBuildRepository
{
    protected function buildPluginsRepo(): IRepository
    {
        $builder = new RepositoryBuilder(getcwd() . '/tests/tmp', __DIR__ . '/../../resources');

        $builder->build([
            "driver" => "\\extas\\components\\repositories\\drivers\\DriverFileJson",
            "options" => [
                "path" => "/tmp/",
                "db" => "system"
            ],
            "tables" => [
                "plugins" => [
                    "namespace" => "tests\\tmp",
                    "item_class"=> "\\extas\\components\\plugins\\Plugin",
                    "pk"=> "name",
                    "aliases"=> ["plugins"]
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
        $builder = new RepositoryBuilder(getcwd() . '/tests/tmp', __DIR__ . '/../../resources');

        $builder->build([
            "driver" => "\\extas\\components\\repositories\\drivers\\DriverFileJson",
            "options" => [
                "path" => "configs/",
                "db" => "system"
            ],
            "tables" => [
                "plugins" => [
                    "namespace" => "tests\\tmp",
                    "item_class"=> "\\extas\\components\\extensions\\Extension",
                    "pk"=> "name",
                    "aliases"=> ["extensions"]
                ]
            ]
        ]);

        return new tests\tmp\RepositoryExtensions();
    }

    protected function removePExtenionsRepo(): void
    {
        unlink(getcwd() . '/tests/tmp/RepositoryExtensions.php');
    }
}
