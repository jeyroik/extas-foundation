<?php
namespace tests;

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
        $builder = new RepositoryBuilder(getcwd() . '/tests/tmp', __DIR__ . '/../resources');

        $builder->build([
            "driver" => "\\extas\\components\\repositories\\drivers\\DriverFileJson",
            "options" => [
                "path" => "configs/",
                "db" => "system"
            ],
            "tables" => [
                "plugins2" => [
                    "namespace" => "tests\\tmp",
                    "item_class"=> "\\extas\\components\\plugins\\Plugin",
                    "pk"=> "name",
                    "aliases"=> ["plugins2"],
                    "hooks" => [
                        "one-before-hook" => true,
                        "one-after-hook" => true,
                        "all-before-hook" => true,
                        "all-after-hook" => true,
                        "create-before-hook" => true,
                        "create-after-hook" => true,
                        "update-before-hook" => true,
                        "update-after-hook" => true,
                        "delete-before-hook" => true,
                        "delete-after-hook" => true,
                        "drop-before-hook" => true,
                        "drop-after-hook" => true,
                    ]
                ]
            ]
        ]);
        
        $this->assertEquals(
            file_get_contents(getcwd() . '/tests/tmp/RepositoryPlugins2.php'),
            file_get_contents(getcwd() . '/tests/resources/RepositoryPlugins2.php')
        );

        unlink(getcwd() . '/tests/tmp/RepositoryPlugins2.php');
    }
}
