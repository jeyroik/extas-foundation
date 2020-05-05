<?php
namespace extas\components;

use extas\interfaces\IItem;

/**
 * Trait TIsArray
 *
 * @property $config
 *
 * @package extas\components
 * @author jeyroik <jeyroik@gmail.com>
 */
trait TAsArray
{
    /**
     * @var int
     */
    protected int $currentKey = 0;

    /**
     * @var array
     */
    protected array $keyMap = [];

    /**
     * @param array $data
     * @return $this
     */
    public function __merge(array $data)
    {
        foreach ($data as $key => $value) {
            $this->config[$key] = $value;
        }

        return $this;
    }

    /**
     * @param mixed $offset
     *
     * @return bool
     */
    public function offsetExists($offset)
    {
        return isset($this->config[$offset]);
    }

    /**
     * @param mixed $offset
     *
     * @return mixed|null
     */
    public function offsetGet($offset)
    {
        return $this->config[$offset] ?? null;
    }

    /**
     * @param mixed $offset
     * @param mixed $value
     */
    public function offsetSet($offset, $value)
    {
        $this->config[$offset] = $value;
    }

    /**
     * @param mixed $offset
     */
    public function offsetUnset($offset)
    {
        unset($this->config[$offset]);
    }

    /**
     * @return bool
     */
    public function valid()
    {
        return isset($this->keyMap[$this->currentKey]);
    }

    /**
     * @return string|null
     */
    public function key()
    {
        return $this->keyMap[$this->currentKey] ?? null;
    }

    /**
     * @return void
     */
    public function next()
    {
        $this->currentKey++;
    }

    /**
     * @return mixed
     */
    public function current()
    {
        return $this->config[$this->keyMap[$this->currentKey]];
    }

    /**
     * @return void
     */
    public function rewind()
    {
        $this->currentKey = 0;
    }
}