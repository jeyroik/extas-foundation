<?php
namespace extas\interfaces\repositories\drivers;

use extas\interfaces\IItem;
use League\Monga\Query\Where;

/**
 * Interface IDriver
 *
 * @package extas\interfaces\repositories\clients
 * @author jeyroik@gmail.com
 */
interface IDriver
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
     * @param array|Where $query
     * @param int $offset
     * @param array $fields
     *
     * @return IItem|null
     */
    public function findOne(array $query = [], int $offset = 0, array $fields = []);

    /**
     * @param array|Where $query
     * @param int $limit
     * @param int $offset
     * @param array $orderBy [fieldName, asc/desc]
     * @param array $fields
     *
     * @return IItem[]
     */
    public function findAll(
        array $query = [],
        int $limit = 0,
        int $offset = 0,
        array $orderBy = [],
        array $fields = []
    );

    /**
     * @return bool
     */
    public function drop(): bool;

    /**
     * @return string
     */
    public function getPk(): string;

    /**
     * @return string
     */
    public function getItemClass(): string;

    /**
     * @return string
     */
    public function getTableName(): string;

    /**
     * @param array $groupBy
     *
     * @return $this
     */
    public function group(array $groupBy);
}
