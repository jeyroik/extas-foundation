<?php
namespace extas\components;
use extas\interfaces\IHasClass;

/**
 * Trait THasClass
 *
 * @property $config
 *
 * @package extas\components
 * @author jeyroik@gmail.com
 */
trait THasClass
{
    /**
     * @return string
     */
    public function getClass(): string
    {
        return $this->config[IHasClass::FIELD__CLASS] ?? '';
    }

    /**
     * @param string $class
     *
     * @return $this
     */
    public function setClass(string $class)
    {
        $this->config[IHasClass::FIELD__CLASS] = $class;

        return $this;
    }

    /**
     * @param array $parameters
     *
     * @return mixed
     */
    public function buildClassWithParameters(array $parameters = [])
    {
        $className = $this->getClass();

        return new $className($parameters);
    }
}
