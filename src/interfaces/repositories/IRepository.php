<?php
namespace extas\interfaces\repositories;

use extas\interfaces\IItem;

/**
 * Interface IRepository
 *
 * @package extas\interfaces\repositories
 * @author jeyroik@gmail.com
 */
interface IRepository extends IItem
{
    const OPTION__REPOSITORY_NAME = 'repository.name';
    const OPTION__REPOSITORY_SCOPE = 'repository.scope';
    const OPTION__COLLECTION_UID = 'collection.uid';
    const OPTION__ITEM_CLASS = 'item.class';

    /**
     * @param $where
     *
     * @return mixed
     */
    public function one($where);

    /**
     * @param $where
     *
     * @return array
     */
    public function all($where);

    /**
     * @param $item
     *
     * @return mixed
     */
    public function create($item);

    /**
     * @param $item
     * @param $where
     *
     * @return int
     */
    public function update($item, $where = []): int;

    /**
     * @param $where
     * @param $item
     *
     * @return int
     */
    public function delete($where, $item = null): int;

    /**
     * @param string $byField
     * @param string|array $returnFields
     *
     * @return array
     */
    public function group($byField, $returnFields);

    /**
     * @return bool
     */
    public function drop(): bool;
}
