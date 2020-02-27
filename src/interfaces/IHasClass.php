<?php
namespace extas\interfaces;

/**
 * Interface IHasClass
 *
 * @package extas\interfaces
 * @author jeyroik@gmail.com
 */
interface IHasClass
{
    public const FIELD__CLASS = 'class';

    /**
     * @return string
     */
    public function getClass(): string;

    /**
     * @param string $class
     *
     * @return $this
     */
    public function setClass(string $class);

    /**
     * @param array $parameters
     *
     * @return mixed
     */
    public function buildClassWithParameters(array $parameters = []);
}
