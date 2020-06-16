<?php

use extas\components\plugins\Plugin;

/**
 * Class PluginTest
 * @author jeyroik <jeyroik@gmail.com>
 */
class PluginTest extends \PHPUnit\Framework\TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        $env = \Dotenv\Dotenv::create(getcwd() . '/tests/');
        $env->load();
    }

    public function testSetAndGetStage()
    {
        $plugin = new Plugin();
        $plugin->setStage('stage');
        $plugin->setPriority(10);
        $this->assertEquals('stage', $plugin->getStage());
        $this->assertEquals(10, $plugin->getPriority());
    }
}
