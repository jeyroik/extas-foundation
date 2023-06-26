<?php
namespace tests\crawlers;

use extas\components\crawlers\CrawlerStorage;
use PHPUnit\Framework\TestCase;

/**
 * Class CrawlerStorageTest
 *
 * @package tests\crawlers
 * @author jeyroik <jeyroik@gmail.com>
 */
class CrawlerStorageTest extends TestCase
{
    public function testCrawling()
    {
        $crawler = new CrawlerStorage('extas.app.storage.test.json', 'extas.storage.test.json');
        list($app, $packages) = $crawler(__DIR__ . '/../resources');

        $appMust = $this->getAppConfig();
        $packagesMust = $this->getPackagesConfig();

        $this->assertEquals($appMust, $app, 'Incorrect app crawling: ' . print_r($app, true));
        $this->assertEquals($packagesMust, $packages, 'Incorrect packages crawling: ' . print_r($packages, true));
    }

    protected function getPackagesConfig(): array
    {
        return [
            "some/package" => [
                "name" => "some/package",
                "tables" => [
                    "entries" => [
                        "namespace" => "tests\\tmp_install",
                        "item_class" => "\\extas\\components\\plugins\\Plugin",
                        "pk" => "name",
                        "aliases" => ["entries"],
                        "code" => [
                            "create-before" => '\\extas\\components\\repositories\\RepoItem::throwIfExist($this, $item, [\'class\']);'
                        ]
                    ]
                ]
            ]
        ];
    }

    protected function getAppConfig(): array
    {
        return [
            "name" => "some/app",
            "drivers" => [
                [
                    "class" => "\\extas\\components\\repositories\\drivers\\DriverFileJson",
                    "options" => [
                        "path" => "tests/tmp/",
                        "db" => "system"
                    ],
                    "tables" => [
                        "plugins", "extensions", "entries"
                    ]
                ]
            ],
            "tables" => [
                "plugins" => [
                    "namespace" => "tests\\tmp_install",
                    "item_class" => "\\extas\\components\\plugins\\Plugin",
                    "pk" => "name",
                    "code" => [
                        "create-before" => '\\extas\\components\\repositories\\RepoItem::throwIfExist($this, $item, [\'class\']);',
                        "drop-after" => "\\extas\\components\\Plugins::reset();"
                    ]
                ], 
                "extensions" => [
                    "namespace" => "tests\\tmp_install",
                    "item_class" => "\\extas\\components\\extensions\\Extension",
                    "pk" => "name",
                    "code" => [
                        "create-before" => '\\extas\\components\\repositories\\RepoItem::throwIfExist($this, $item, [\'class\']);'
                    ]
                ]
            ],
            "plugins" => [
                [
                    "class" => "tests\\resources\\PluginCheckStage",
                    "stage" => "extas.before.install.entity"
                ],
                [
                    "class" => "tests\\resources\\PluginIsInstall",
                    "stage" => "extas.is.install.entity"
                ],
                [
                    "class" => "testP",
                    "stage" => "testP"
                ]
            ],
            "extensions" => [
                [
                    "class" => "testE",
                    "interface" => "itestE",
                    "subject" => "test",
                    "methods" => ["some"]
                ]
            ]
        ];
    }
}
