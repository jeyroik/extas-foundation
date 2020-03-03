<?php
namespace extas\components\repositories\clients\databases;

use extas\interfaces\repositories\clients\IClientDatabase;
use extas\interfaces\repositories\clients\IClientTable;
use extas\components\repositories\clients\ClientTableMongo;

/**
 * Class ClientDatabaseMongo
 *
 * @package extas\components\repositories\clients
 * @author jeyroik@gmail.com
 */
class ClientDatabaseMongo implements IClientDatabase
{
    /**
     * @var \MongoDB[]
     */
    protected static array $dbs = [];

    /**
     * @var IClientTable[]
     */
    protected static array $tables = [];

    /**
     * @var string
     */
    protected string $curDB = '';

    /**
     * ClientDatabaseMongo constructor.
     *
     * @param $client \MongoClient
     * @param string $name
     */
    public function __construct($client, string $name)
    {
        if (!isset(static::$dbs[$name])) {
            static::$dbs[$name] = $client->selectDB($name);
        }

        $this->curDB = $name;
    }

    /**
     * @param string $tableName
     *
     * @return IClientTable
     * @throws
     */
    public function getTable(string $tableName): IClientTable
    {
        if (!isset(static::$tables[$this->curDB . '.' . $tableName])) {
            static::$tables[$this->curDB . '.' . $tableName] = new ClientTableMongo(
                static::$dbs[$this->curDB]->selectCollection($tableName)
            );
        }

        return static::$tables[$this->curDB . '.' . $tableName];
    }
}
