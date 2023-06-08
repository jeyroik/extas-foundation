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
    public function offsetExists(mixed $offset): bool
    {
        return isset($this->config[$offset]);
    }

    /**
     * @param mixed $offset
     *
     * @return mixed|null
     */
    public function offsetGet(mixed $offset): mixed
    {
        return $this->config[$offset] ?? null;
    }

    /**
     * @param mixed $offset
     * @param mixed $value
     */
    public function offsetSet(mixed $offset, mixed $value): void
    {
        $this->config[$offset] = $value;
        $this->keyMap = array_keys($this->config);
        $this->currentKey = 0;
    }

    /**
     * @param mixed $offset
     */
    public function offsetUnset(mixed $offset): void
    {
        unset($this->config[$offset]);
    }

    /**
     * @return bool
     */
    public function valid(): bool
    {
        return isset($this->keyMap[$this->currentKey]);
    }

    /**
     * @return string|null
     */
    public function key(): mixed
    {
        return $this->keyMap[$this->currentKey] ?? null;
    }

    /**
     * @return void
     */
    public function next(): void
    {
        $this->currentKey++;
    }

    /**
     * @return mixed
     */
    public function current(): mixed
    {
        return $this->config[$this->keyMap[$this->currentKey]];
    }

    /**
     * @return void
     */
    public function rewind(): void
    {
        $this->currentKey = 0;
    }
}