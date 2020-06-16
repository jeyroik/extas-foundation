<?php

use \PHPUnit\Framework\TestCase;
use \extas\components\repositories\clients\Client;

/**
 * Class ClientTest
 *
 * @author jeyroik <jeyroik@gmail.com>
 */
class ClientTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        $env = \Dotenv\Dotenv::create(getcwd() . '/tests/');
        $env->load();
    }

    public function testDsnAsStringApplying()
    {
        $client = new class ('my://local:90') extends Client {
            protected string $clientName = 'test';

            public function getDsn()
            {
                return $this->dsn;
            }

            public function getDb($dbName)
            {
                // TODO: Implement getDb() method.
            }
        };

        $this->assertEquals('my://local:90', $client->getDsn());
    }

    public function testDsnAsArrayApplying()
    {
        $client = new class ([
            Client::FIELD__HOST => 'host',
            Client::FIELD__PORT => 8080
        ]) extends Client {
            protected string $clientName = 'test';

            public function getDsn()
            {
                return $this->dsn;
            }

            public function getDb($dbName)
            {

            }
        };

        $this->assertEquals('test://host:8080', $client->getDsn());
    }

    public function testEmptyDsn()
    {
        $this->expectExceptionMessage('Missed or unknown dsn');
        new class ('') extends Client {
            protected string $clientName = 'test';

            public function getDb($dbName)
            {

            }
        };
    }
}
