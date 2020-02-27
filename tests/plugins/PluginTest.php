<?php

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
}
