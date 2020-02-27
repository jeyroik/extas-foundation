<?php
namespace extas\interfaces\repositories\clients;

/**
 * Interface IClient
 *
 * @package extas\interfaces\repositories\clients
 * @author jeyroik@gmail.com
 */
interface IClient
{
    public const FIELD__HOST = 'host';
    public const FIELD__PORT = 'port';

    /**
     * IClient constructor.
     *
     * @param array|string $dsn
     */
    public function __construct($dsn);

    /**
     * @param $dbName
     *
     * @return IClientDatabase
     */
    public function getDb($dbName);
}
