<?php
namespace extas\components\repositories\clients;

use extas\interfaces\repositories\clients\IClientDatabase;
use extas\interfaces\repositories\clients\IClientTable;

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
    protected static $dbs = [];

    /**
     * @var IClientTable[]
     */
    protected static $tables = [];

    /**
     * @var string
     */
    protected $curDB = '';

    /**
     * ClientDatabaseMongo constructor.
     *
     * @param $client \MongoClient
     * @param $name
     */
    public function __construct($client, $name)
    {
        if (!isset(static::$dbs[$name])) {
            static::$dbs[$name] = $client->selectDB($name);
        }

        $this->curDB = $name;
    }

    /**
     * @param string $tableName
     *
     * @return IClientTable|\MongoCollection
     */
    public function getTable($tableName)
    {
        if (!isset(static::$tables[$this->curDB . '.' . $tableName])) {
            static::$tables[$this->curDB . '.' . $tableName] = new ClientTableMongo(
                static::$dbs[$this->curDB]->selectCollection($tableName)
            );
        }

        return static::$tables[$this->curDB . '.' . $tableName];
    }
}
