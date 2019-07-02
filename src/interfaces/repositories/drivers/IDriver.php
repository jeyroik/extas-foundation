<?php
namespace extas\interfaces\repositories\drivers;

use extas\interfaces\repositories\clients\IClient;

/**
 * Interface IDriver
 *
 * @package extas\interfaces\repositories\drivers
 * @author aivanov@fix.ru
 */
interface IDriver
{
    const FIELD__NAME = 'name';
    const FIELD__CLASS = 'class';

    /**
     * @return string
     */
    public function getName(): string;

    /**
     * @return string
     */
    public function getClass(): string;

    /**
     * @param $name string
     *
     * @return $this
     */
    public function setName($name);

    /**
     * @param $class string
     *
     * @return $this
     */
    public function setClass($class);

    /**
     * @param $config array
     *
     * @return IClient
     */
    public function createClient($config = []);
}
