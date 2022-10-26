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
    public static function getItem(string $name);

    /**
     * @param string $name
     *
     * @return bool
     */
    public static function hasItem(string $name): bool;

    /**
     * @param string $alias
     * @param string $class
     *
     * @return void
     */
    public static function saveItem($alias, $class): void;

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
