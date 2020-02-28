<?php

use \PHPUnit\Framework\TestCase;
use \extas\components\SystemContainer;

/**
 * Class SystemContainerTest
 * @author jeyroik <jeyroik@gmail.com>
 */
class SystemContainerTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        $env = \Dotenv\Dotenv::create(getcwd() . '/tests/');
        $env->load();
    }

    public function testAddClass()
    {
        SystemContainer::addItem(__FILE__, static::class);
        $this->assertEquals(true, SystemContainer::hasItem(__FILE__));

        $self = SystemContainer::getItem(__FILE__);
        $this->assertEquals(static::class, get_class($self));
    }
}
