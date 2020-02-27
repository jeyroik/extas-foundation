<?php
namespace extas\components\repositories\clients;

/**
 * Class ClientMongo
 *
 * @package extas\components\repositories\clients
 * @author jeyroik@gmail.com
 */
class ClientMongo extends Client
{
    protected static array $instances = [];

    protected string $clientName = 'mongodb';

    /**
     * @param $dbName
     *
     * @return mixed
     * @throws
     */
    public function getDb($dbName)
    {
        $key = $this->dsn . '.' . $dbName;

        return isset(static::$instances[$key])
            ? static::$instances[$key]
            : static::$instances[$key] = new ClientDatabaseMongo(new \MongoClient($this->dsn), $dbName);
    }
}
