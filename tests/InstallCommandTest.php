<?php
namespace tests;

use extas\components\Item;
use extas\components\Json;
use extas\components\plugins\Plugin;
use extas\components\repositories\RepositoryBuilder;
use PHPUnit\Framework\TestCase;

/**
 * Class JsonTest
 *
 * @package tests
 * @author jeyroik@gmail.com
 */
class InstallCommandTest extends TestCase
{
    public function testRepoBuilder()
    {
        $builder = new RepositoryBuilder(getcwd() . '/extas_build', __DIR__ . '/../resources');
        $builder->build([
            "driver" => "\\extas\\components\\repositories\\drivers\\DriverFileJson",
            "options" => [
                "path" => "configs/",
                "db" => "system"
            ],
            "tables" => [
                "plugins" => [
                    "item_class"=> "\\extas\\components\\plugins\\Plugin",
                    "pk"=> "name",
                    "aliases"=> ["plugins"]
                ]
            ]
        ]);
        $this->assertEquals(file_get_contents(getcwd() . '/extas_build/RepositoryPlugins.php'), '');
    }
}