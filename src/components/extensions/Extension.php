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
     * @var array
     */
    public $methods = [];
    public $subject = '';

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
     * @return array
     */
    public function getMethodsNames()
    {
        return $this->methods;
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
     * @param $subject
     *
     * @return $this
     */
    public function setSubject($subject)
    {
        $this->config[static::FIELD__SUBJECT] = $subject;

        return $this;
    }

    /**
     * @param $interface
     *
     * @return $this
     */
    public function setInterface($interface)
    {
        $this->config[static::FIELD__INTERFACE] = $interface;

        return $this;
    }

    /**
     * @param $methods
     *
     * @return $this
     */
    public function setMethods($methods)
    {
        $this->config[static::FIELD__METHODS] = $methods;
        $this->methods = $methods;

        return $this;
    }

    /**
     * @param $class
     *
     * @return $this
     */
    public function setClass($class)
    {
        $this->config[static::FIELD__CLASS] = $class;

        return $this;
    }

    /**
     * @param $id
     *
     * @return $this
     */
    public function setId($id)
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
