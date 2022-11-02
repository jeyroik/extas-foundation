<?php
namespace tests\stages;

use extas\components\crawlers\CrawlerEntities;
use PHPUnit\Framework\TestCase;

/**
 * Class CrawlerEntitiesTest
 *
 * @package tests\stages
 * @author jeyroik <jeyroik@gmail.com>
 */
class CrawlerEntitiesTest extends TestCase
{
    public function testCrawling()
    {
        $crawler = new CrawlerEntities('extas.app.test.json', 'extas.test.json');
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
                "entries" => [
                    [
                        "class" => "package__some"
                    ]
                ]
            ]
        ];
    }

    protected function getAppConfig(): array
    {
        return [
            "name" => "some/app",
            "entries" => [
                [
                    "class" => "app__some"
                ]
            ]
        ];
    }
}
