<?php
namespace extas\components\repositories\clients;

use extas\interfaces\repositories\clients\IClient;

/**
 * Class Client
 *
 * @package extas\components\repositories\clients
 * @author aivanov@fix.ru
 */
abstract class Client implements IClient
{
    protected $dsn = '';
    protected $clientName = '';

    /**
     * Client constructor.
     *
     * @param array $dsn
     *
     * @throws
     */
    public function __construct($dsn)
    {
        if (empty($dsn)) {
            throw new \Exception('Empty dsn');
        }

        if (is_array($dsn)) {
            $host = $dsn[static::FIELD__HOST] ?? '';
            $port = $dsn[static::FIELD__PORT] ?? '';
            $dsn = $this->clientName . '://' .$host . ':' . $port;
        }

        $this->dsn = (string) $dsn;
    }
}
