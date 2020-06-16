<?php
namespace extas\interfaces\extensions;

/**
 * Interface IExtendable
 *
 * @package extas\interfaces\extensions
 * @author jeyroik@gmail.com
 */
interface IExtendable
{
    /**
     * @param string $interface
     *
     * @return bool
     */
    public function isImplementsInterface(string $interface): bool;

    /**
     * @param string $methodName
     * @return bool
     */
    public function hasMethod(string $methodName): bool;
}
