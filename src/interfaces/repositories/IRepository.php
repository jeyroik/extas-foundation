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
    public const OPTION__REPOSITORY_NAME = 'repository.name';
    public const OPTION__REPOSITORY_SCOPE = 'repository.scope';
    public const OPTION__COLLECTION_UID = 'collection.uid';
    public const OPTION__ITEM_CLASS = 'item.class';

    /**
     * @return string
     */
    public function getScope(): string;

    /**
     * @return string
     */
    public function getName(): string;

    /**
     * @return string
     */
    public function getPk(): string;

    /**
     * @return string
     */
    public function getItemClass(): string;

    /**
     * @param $where
     * @param int $offset
     * @param array $fields
     *
     * @return mixed
     */
    public function one($where, int $offset = 0, array $fields = []);

    /**
     * @param $where
     * @param int $limit
     * @param int $offset
     * @param array $orderBy [fieldName, asc/desc]
     * @param array $fields
     *
     * @return array
     */
    public function all($where, int $limit = 0, int $offset = 0, array $orderBy = [], array $fields = []);

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
     * @param array $byFields
     *
     * @return $this
     */
    public function group(array $byFields): IRepository;

    /**
     * @return bool
     */
    public function drop(): bool;
}
