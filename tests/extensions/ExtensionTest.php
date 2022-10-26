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

        $ext->setInterface('test-interface')
            ->setSubject('test-suject')
            ->setClass('test-class')
            ->setMethods(['test-method']);

        $this->assertEquals('test-interface', $ext->getInterface());
        $this->assertEquals('test-suject', $ext->getSubject());
        $this->assertEquals('test-class', $ext->getClass());
        $this->assertEquals(['test-method'], $ext->getMethods());

        $this->expectOutputString('Worked nice in ' . static::class);
        $ext->runMethod($this, 'test', ['nice']);
    }
}
