<?php
namespace extas\components\extensions;

use extas\components\Item;
use extas\interfaces\extensions\IExtension;

/**
 * Class Extension
 *
 * @package extas\components\extensions
 * @author jeyroik@gmail.com
 */
class Extension extends Item implements IExtension
{
    /**
     * @param $subject
     * @param string $methodName
     * @param $args
     *
     * @return mixed|null
     */
    public function runMethod(&$subject, $methodName, $args)
    {
        $args[] = &$subject;

        return method_exists($this, $methodName)
            ? call_user_func_array([$this, $methodName], $args)
            : null;
    }

    /**
     * @return string[]
     */
    public function getMethods(): array
    {
        return $this->config[static::FIELD__METHODS] ?? [];
    }

    /**
     * @return string
     */
    public function getClass(): string
    {
        return $this->config[static::FIELD__CLASS] ?? '';
    }

    /**
     * @return string
     */
    public function getInterface(): string
    {
        return $this->config[static::FIELD__INTERFACE] ?? '';
    }

    /**
     * @return string
     */
    public function getSubject(): string
    {
        return $this->config[static::FIELD__SUBJECT] ?? '';
    }

    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->config[static::FIELD__ID] ?? '';
    }

    /**
     * @param string $subject
     *
     * @return $this
     */
    public function setSubject(string $subject)
    {
        $this->config[static::FIELD__SUBJECT] = $subject;

        return $this;
    }

    /**
     * @param string $interface
     *
     * @return $this
     */
    public function setInterface(string $interface)
    {
        $this->config[static::FIELD__INTERFACE] = $interface;

        return $this;
    }

    /**
     * @param array $methods
     *
     * @return $this
     */
    public function setMethods(array $methods)
    {
        $this->config[static::FIELD__METHODS] = $methods;

        return $this;
    }

    /**
     * @param string $class
     *
     * @return $this
     */
    public function setClass(string $class)
    {
        $this->config[static::FIELD__CLASS] = $class;

        return $this;
    }

    /**
     * @param string $id
     *
     * @return $this
     */
    public function setId(string $id)
    {
        $this->config[static::FIELD__ID] = $id;

        return $this;
    }

    /**
     * @return string
     */
    protected function getSubjectForExtension(): string
    {
        return static::SUBJECT;
    }
}
