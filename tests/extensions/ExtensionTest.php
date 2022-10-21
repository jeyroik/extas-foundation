<?php

use \PHPUnit\Framework\TestCase;
use \extas\components\extensions\Extension;
use Dotenv\Dotenv;

/**
 * Class ExtensionTest
 *
 * @author jeyroik@gmail.com
 */
class ExtensionTest extends TestCase
{
    protected function setUp(): void
    {
        $this->markTestSkipped('This test is not updated to the Foundation v6');
        parent::setUp();
        $env = Dotenv::create(getcwd() . '/tests/');
        $env->load();
    }

    public function testRunMethod()
    {
        $ext = new class extends Extension {
            public function test($suffix, $test = null)
            {
                echo 'Worked ' . $suffix . ' in ' . get_class($test);
            }
        };

        $this->expectOutputString('Worked nice in ' . static::class);
        $ext->runMethod($this, 'test', ['nice']);
    }
}
