<?php
namespace extas\components\repositories\clients;

use extas\components\exceptions\MissedOrUnknown;
use extas\interfaces\repositories\clients\IClient;

/**
 * Class Client
 *
 * @package extas\components\repositories\clients
 * @author jeyroik@gmail.com
 */
abstract class Client implements IClient
{
    protected string $dsn = '';
    protected string $clientName = '';

    /**
     * Client constructor.
     *
     * @param $dsn
     * @throws MissedOrUnknown
     */
    public function __construct($dsn)
    {
        if (empty($dsn)) {
            throw new MissedOrUnknown('dsn');
        }

        if (is_array($dsn)) {
            $host = $dsn[static::FIELD__HOST] ?? '';
            $port = $dsn[static::FIELD__PORT] ?? '';
            $dsn = $this->clientName . '://' .$host . ':' . $port;
        }

        $this->dsn = (string) $dsn;
    }
}
