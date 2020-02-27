<?php
namespace extas\interfaces\repositories\clients;

use extas\interfaces\IItem;

/**
 * Interface IClientTable
 *
 * @package extas\interfaces\repositories\clients
 * @author jeyroik@gmail.com
 */
interface IClientTable
{
    public const FIELD__REPO_NAME = 'repo.name';
    public const FIELD__SCOPE = 'scope';
    public const FIELD__PRIMARY_KEY = 'pk';

    /**
     * @param $item IItem
     *
     * @return IItem|\Exception
     */
    public function insert($item);

    /**
     * @param $query array conditions or IItem[]
     * @param $data
     *
     * @return int
     */
    public function updateMany($query, $data);

    /**
     * @param $item IItem
     *
     * @return bool|\Exception
     */
    public function update($item): bool;

    /**
     * @param $query array conditions or IItem[]
     *
     * @return mixed
     */
    public function deleteMany($query);

    /**
     * @param $item IItem
     *
     * @return bool|\Exception
     */
    public function delete($item): bool;

    /**
     * @param array $query
     *
     * @return IItem|null
     */
    public function findOne($query = []);

    /**
     * @param array $query
     *
     * @return IItem[]
     */
    public function findAll($query = []);

    /**
     * @return bool
     */
    public function drop(): bool;

    /**
     * @param $pk
     *
     * @return $this
     */
    public function setPk($pk);

    /**
     * @return string
     */
    public function getPk(): string;

    /**
     * @param $itemClass
     *
     * @return $this
     */
    public function setItemClass($itemClass);

    /**
     * @return string
     */
    public function getItemClass(): string;

    /**
     * @param $fieldName string
     *
     * @return $this
     */
    public function setIdAs($fieldName);

    /**
     * @return string
     */
    public function getIdAs();

    /**
     * @param string $groupBy
     * @param string|array $fields
     *
     * @return array
     */
    public function group($groupBy, $fields);
}
