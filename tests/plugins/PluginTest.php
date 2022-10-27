<?php

use extas\components\plugins\Plugin;

/**
 * Class PluginTest
 * @author jeyroik <jeyroik@gmail.com>
 */
class PluginTest extends \PHPUnit\Framework\TestCase
{
    public function testSetAndGetStage()
    {
        $plugin = new Plugin();
        $plugin->setStage('stage');
        $plugin->setPriority(10);
        $plugin->setHash('test');
        $this->assertEquals('stage', $plugin->getStage());
        $this->assertEquals(10, $plugin->getPriority());
        $this->assertEquals('test', $plugin->getHash());
    }
}
