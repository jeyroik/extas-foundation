<?php
namespace extas\components\collections;

use extas\components\exceptions\AlreadyExist;
use extas\components\exceptions\MissedOrUnknown;

/**
 * @implements extas\interfaces\collections\ICollection
 * @property array $config
 */
trait TCollection
{
    /**
     * Return $default if item is missed.
     */
    public function getOne(string $name, array $default = []): array
    {
        return $this->config[$name] ?? $default;
    }

    /**
     * Throw an error if item is missed and $errorOnMissed is true.
     * 
     * @throws MissedOrUnknown
     */
    public function hasOne(string $name, bool $errorOnMissed = false): bool
    {
        if (isset($this[$name])) {
            return true;
        }

        if ($errorOnMissed) {
            throw new MissedOrUnknown('parameter "' . $name . '"');
        }

        return false;
    }

    /**
     * Throw an error if item is already exist.
     * 
     * @throws AlreadyExist
     */
    public function addOne(string $name, array $item): bool
    {
        if ($this->hasOne($name)) {
            throw new AlreadyExist('parameter "' . $name . '"');
        }

        $this[$name] = $item;

        return true;
    }

    /**
     * Don't throw an error if an item is missed.
     */
    public function replaceOne(string $name, array $item): bool
    {
        $this[$name] = $item;

        return true;
    }

    /**
     * Don't throw an error if item is missed.
     */
    public function removeOne(string $name): bool
    {
        if ($this->hasOne($name)) {
            unset($this[$name]);

            return true;
        }

        return false;
    }
    
    /**
     * Return $default if item is missed.
     * 
     * @return Array[]
     */
    public function getAll(array $names = [], array $default = []): array
    {
        if (empty($names)) {
            return $this->config;
        }

        $filtered = [];

        foreach ($names as $name) {
            $filtered[$name] = $this->config[$name] ?? $default;
        }

        return $filtered;
    }

    /**
     * Only if all items with $names exist return true.
     * 
     * Throw an error if at least one item is missed and $errorOnMissed is true
     */
    public function hasAll(array $names = [], bool $errorOnMissed = false): bool
    {
        foreach ($names as $name) {
            if (!$this->hasOne($name, $errorOnMissed)) {
                return false;
            }
        }

        return true;
    }

    /**
     * Throws an error if at least one of items is already exist.
     * 
     * @param array $items [<item.name> => [...]]
     * 
     * @throws AlreadyExist
     */
    public function addAll(array $items): bool
    {
        foreach ($items as $name => $item) {
            $this->addOne($name, $item);
        }

        return true;
    }

    /**
     * @param array $items [<item.name> => [...]]
     */
    public function replaceAll(array $items): bool
    {
        foreach ($items as $name => $item) {
            $this->replaceOne($name, $item);
        }

        return true;
    }

    /**
     * Don't throw an error if an item is already missed.
     */
    public function removeAll(...$names): bool
    {
        foreach ($names as $name) {
            $this->removeOne($name);
        }

        return true;
    }
}
