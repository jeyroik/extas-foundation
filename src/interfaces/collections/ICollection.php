<?php
namespace extas\interfaces\collections;

use extas\components\exceptions\MissedOrUnknown;

interface ICollection
{
    /**
     * Return $default if item is missed.
     */
    public function getOne(string $name, array $default = []): array;

    /**
     * Throw an error if item is missed and $errorOnMissed is true.
     * 
     * @throws MissedOrUnknown
     */
    public function hasOne(string $name, bool $errorOnMissed = false): bool;

    /**
     * Throw an error if item is already exist.
     * 
     * @throws AlreadyExist
     */
    public function addOne(string $name, array $item): bool;

    /**
     * Don't throw an error if an item is missed.
     */
    public function replaceOne(string $name, array $item): bool;

    /**
     * Don't throw an error if item is missed.
     */
    public function removeOne(string $name): bool;
    
    /**
     * Return $default if item missed.
     * 
     * @return Array[]
     */
    public function getAll(array $names = [], array $default = []): array;

    /**
     * Only if all items with $names exist return true.
     * 
     * Throw an error if at least one item is missed and $errorOnMissed is true
     */
    public function hasAll(array $names = [], bool $errorOnMissed = false): bool;

    /**
     * Throws an error if at least one of items is already exist.
     * 
     * @param array $items [<item.name> => [...]]
     * 
     * @throws AlreadyExist
     */
    public function addAll(array $items): bool;

    /**
     * @param array $items [<item.name> => [...]]
     */
    public function replaceAll(array $items): bool;

    /**
     * Don't throw an error if an item is already missed.
     */
    public function removeAll(...$names): bool;
}
