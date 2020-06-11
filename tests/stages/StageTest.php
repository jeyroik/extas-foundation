<?php
namespace tests\stages;

use extas\components\stages\Stage;

use Dotenv\Dotenv;
use PHPUnit\Framework\TestCase;

/**
 * Class StageTest
 *
 * @package tests\stages
 * @author jeyroik <jeyroik@gmail.com>
 */
class StageTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        $env = Dotenv::create(getcwd() . '/tests/');
        $env->load();
    }

    public function testBasicLogic()
    {
        $stage = new Stage();
        $stage->setInput('string input, int test, alone');
        $stage->setOutput('string output, int test, alone');
        $this->assertEquals([
            [
                'type' => 'string',
                'arg' => 'input'
            ],
            [
                'type' => 'int',
                'name' => 'test'
            ],
            [
                'type' => '',
                'name' => 'alone'
            ]
        ], $stage->getInputAsArray(), 'Current input: ' . print_r($stage->getInputAsArray(), true));
        $this->assertEquals([
            [
                'type' => 'string',
                'arg' => 'input'
            ],
            [
                'type' => 'int',
                'name' => 'test'
            ],
            [
                'type' => '',
                'name' => 'alone'
            ]
        ], $stage->getOutputAsArray(), 'Current input: ' . print_r($stage->getOutputAsArray(), true));
    }
}
