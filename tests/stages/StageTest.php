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
        $stage->setOutput('string input, int test, alone');
        $this->assertEquals(
            $this->getWright(),
            $stage->getInputAsArray(),
            'Current input: ' . print_r($stage->getInputAsArray(), true)
        );
        $this->assertEquals(
            $this->getWright(),
            $stage->getOutputAsArray(),
            'Current input: ' . print_r($stage->getOutputAsArray(), true)
        );
    }

    /**
     * @return string[][]
     */
    protected function getWright(): array
    {
        return [
            [
                'type' => 'string',
                'arg' => 'input'
            ],
            [
                'type' => 'int',
                'arg' => 'test'
            ],
            [
                'type' => '',
                'arg' => 'alone'
            ]
        ];
    }
}
