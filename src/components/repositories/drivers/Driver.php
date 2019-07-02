<?php
namespace extas\components\repositories\drivers;

use extas\interfaces\repositories\clients\IClient;
use extas\interfaces\repositories\drivers\IDriver;

/**
 * Class Driver
 *
 * @package extas\components\repositories\drivers
 * @author aivanov@fix.ru
 */
abstract class Driver implements IDriver
{
    /**
     * @var array
     */
    protected $config = [];

    /**
     * @var string
     */
    protected $clientClass = '';

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->config[static::FIELD__NAME] ?? '';
    }

    /**
     * @return string
     */
    public function getClass(): string
    {
        return $this->config[static::FIELD__CLASS] ?? '';
    }

    /**
     * @param string $name
     *
     * @return $this
     */
    public function setName($name)
    {
        $this->config[static::FIELD__NAME] = $name;

        return $this;
    }

    /**
     * @param string $class
     *
     * @return $this
     */
    public function setClass($class)
    {
        $this->config[static::FIELD__CLASS] = $class;

        return $this;
    }

    /**
     * @param array $config
     *
     * @return IClient
     */
    public function createClient($config = [])
    {
        $clientClass = $this->clientClass;

        return new $clientClass($config);
    }

    /**
     * @param $string
     *
     * @return bool
     */
    protected function isNotDsn($string)
    {
        return strpos($string, '://') === false;
    }
}
