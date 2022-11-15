<?php
namespace tests;

use extas\components\repositories\RepositoryBuilder;
use extas\components\SystemContainer;
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
        $builder = new RepositoryBuilder([
            RepositoryBuilder::FIELD__PATH_SAVE => 'tests/tmp',
            RepositoryBuilder::FIELD__PATH_TEMPLATE => __DIR__ . '/../resources'
        ]);

        $builder->build([
            "class" => "\\extas\\components\\repositories\\drivers\\DriverFileJson",
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
                        "one-before" => true,
                        "one-after" => true,
                        "one-as-array-before" => true,
                        "one-as-array-after" => true,
                        "all-before" => true,
                        "all-after" => true,
                        "all-as-array-before" => true,
                        "all-as-array-after" => true,
                        "create-before" => true,
                        "create-after" => true,
                        "update-before" => true,
                        "update-after" => true,
                        "delete-before" => true,
                        "delete-after" => true,
                        "drop-before" => true,
                        "drop-after" => true
                    ],
                    "code" => [
                        "one-before" => "//one-before-code",
                        "one-after" => "//one-after-code",
                        "one-as-array-before" => "//one-as-array-before-code",
                        "one-as-array-after" => "//one-as-array-after-code",
                        "all-before" => "//all-before-code",
                        "all-after" => "//all-after-code",
                        "all-as-array-before" => "//all-as-array-before-code",
                        "all-as-array-after" => "//all-as-array-after-code",
                        "create-before" => "//create-before-code",
                        "create-after" => "//create-after-code",
                        "update-before" => "//update-before-code",
                        "update-after" => "//update-after-code",
                        "delete-before" => "//delete-before-code",
                        "delete-after" => "//delete-after-code",
                        "drop-before" => __DIR__ . '/resources/drop-before-code.php',
                        "drop-after" => "\\extas\\components\\Plugins::reset();"
                    ]
                ]
            ]
        ]);
        
        $this->assertEquals(
            file_get_contents(getcwd() . '/tests/tmp/RepositoryPlugins2.php'),
            file_get_contents(getcwd() . '/tests/resources/RepositoryPlugins2.php')
        );

        unlink(getcwd() . '/tests/tmp/RepositoryPlugins2.php');
        SystemContainer::refresh();
    }
}
