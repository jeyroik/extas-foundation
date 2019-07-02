<?php
namespace extas\interfaces;

/**
 * Interface IContainer
 *
 * @package extas\interfaces
 * @author jeyroik@gmail.com
 */
interface ISystemContainer
{
    /**
     * @param string $name
     *
     * @return mixed
     */
    public static function getItem($name);

    /**
     * @param $name string
     * @param $value
     *
     * @return mixed
     */
    public static function addItem($name, $value);

    /**
     * @return mixed
     */
    public static function reset();
}
