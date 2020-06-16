<?php

use \PHPUnit\Framework\TestCase;
use \extas\components\repositories\clients\databases\DbCurrent;
use \extas\interfaces\repositories\clients\IClientTable;

/**
 * Class DbCurrentTest
 *
 * @author jeyroik <jeyroik@gmail.com>
 */
class DbCurrentTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        $env = \Dotenv\Dotenv::create(getcwd() . '/tests/');
        $env->load();
    }

    public function testGetTable()
    {
        $db = new class extends DbCurrent {
            public static function reset()
            {
                static::$tables = [];
            }

            public static function getTables()
            {
                return static::$tables;
            }
        };

        putenv('TEST_TABLE__NOT_EXISTING_REPO_NAME=tests');
        putenv('TEST_DB__NOT_EXISTING_REPO_NAME=extas_tests');
        putenv('TEST_DSN__NOT_EXISTING_REPO_NAME=mongodb://localhost:27017');
        putenv('TEST_DRIVER__NOT_EXISTING_REPO_NAME=mongo');
        $db::reset();
        $table = $db::getTable('not.existing.repo.name', 'test');
        $this->assertInstanceOf(IClientTable::class, $table);
        $must = [
            'testnot.existing.repo.name' => $table
        ];
        $this->assertEquals($must, $db::getTables());
    }

    public function testGetTableException()
    {
        putenv('TEST_DRIVER__NOT_EXISTING_REPO=not.existing.driver');
        $this->expectExceptionMessage('Missed or unknown driver "not.existing.driver"');
        DbCurrent::getTable('not.existing.repo', 'test');
    }
}
