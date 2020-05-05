<?php
namespace extas\components\extensions;

use extas\components\Item;
use extas\components\THasClass;
use extas\interfaces\extensions\IExtension;

/**
 * Class Extension
 *
 * @package extas\components\extensions
 * @author jeyroik@gmail.com
 */
class Extension extends Item implements IExtension
{
    use THasClass;

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
            : $this->wildcardMethod($methodName, ...$args);
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
     * @param string $methodName
     * @param mixed ...$args
     * @return mixed
     */
    protected function wildcardMethod(string $methodName, ...$args)
    {
        return null;
    }

    /**
     * @return string
     */
    protected function getSubjectForExtension(): string
    {
        return static::SUBJECT;
    }
}
