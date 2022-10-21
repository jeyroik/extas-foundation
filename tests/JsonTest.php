<?php
namespace tests;

use extas\components\Item;
use extas\components\Json;
use extas\components\plugins\Plugin;
use PHPUnit\Framework\TestCase;

/**
 * Class JsonTest
 *
 * @package tests
 * @author jeyroik@gmail.com
 */
class JsonTest extends TestCase
{
    public function testEncode()
    {
        $this->markTestSkipped('This test is not updated to the Foundation v6');
        $this->assertEquals(json_encode(['test']), Json::encode(['test']));
        $item = new class ([
            'test' => 'is ok'
        ]) extends Item {
            protected function getSubjectForExtension(): string
            {
                return '';
            }
        };
        $this->assertEquals(json_encode($item->__toArray()), Json::encode($item));
    }

    public function testDecode()
    {
        $this->assertEquals(['test'], Json::decode(json_encode(['test']), true));

        $pluginData = [
            'class' => 'NotExistingClass',
            'stage' => 'not.existing.stage',
            Json::MARKER__CLASS => Plugin::class
        ];

        /**
         * @var Plugin $plugin
         */
        $plugin = Json::decode(json_encode($pluginData), true);
        $this->assertTrue($plugin instanceof Plugin);
        $this->assertEquals('NotExistingClass', $plugin->getClass());
        $this->assertEquals('not.existing.stage', $plugin->getStage());

        $std = Json::decode('{"test":1}');
        $this->assertTrue($std instanceof \stdClass);
    }
}
