<?php
namespace extas\components\repositories\clients;

use extas\interfaces\repositories\clients\IClientTable;

/**
 * Class ClientTableMongo
 *
 * @package extas\components\repositories\clients
 * @author jeyroik@gmail.com
 */
class ClientTableMongo implements IClientTable
{
    /**
     * @var \MongoCollection
     */
    protected $collection = null;

    /**
     * @var string
     */
    protected $pk = '_id';

    /**
     * @var string
     */
    protected $idAs = '_id';

    /**
     * @var string
     */
    protected $itemClass = '';

    /**
     * ClientTableMongo constructor.
     *
     * @param $collection
     */
    public function __construct($collection)
    {
        $this->collection = $collection;
    }

    /**
     * @param array $query
     *
     * @return array|\jeyroik\extas\interfaces\systems\IItem|null
     */
    public function findOne($query = [])
    {
        $record = $this->collection->findOne($query);

        if ($record) {
            $record['_id'] = (string) $record['_id'];
            $itemClass = $this->getItemClass();
            return new $itemClass($record);
        }

        return $record;
    }

    /**
     * @param array $query
     *
     * @return array|\jeyroik\extas\interfaces\systems\IItem[]
     */
    public function findAll($query = [])
    {
        $itemClass = $this->getItemClass();
        $recordsCursor = $this->collection->find($query);
        $records = [];

        while ($recordsCursor->hasNext()) {
            $record = $recordsCursor->getNext();
            $record['_id'] = (string) $record['id'];
            $records[] = new $itemClass($record);
        }

        return $records;
    }

    /**
     * @param \jeyroik\extas\interfaces\systems\IItem $item
     *
     * @return \Exception|\jeyroik\extas\interfaces\systems\IItem|mixed
     */
    public function insert($item)
    {
        $itemData = $item->__toArray();
        $itemClass = get_class($item);

        $this->collection->insert($itemData);

        $idAs = $this->getIdAs();

        if ($idAs) {
            $itemData[$idAs] = $itemData['_id'];
        } else {
            $itemData['_id'] = (string)$itemData['_id'];
        }

        return new $itemClass($itemData);
    }

    /**
     * @param array $query
     * @param $data
     *
     * @return array|bool|int
     */
    public function updateMany($query, $data)
    {
        return $this->collection->update($query, $data);
    }

    /**
     * @param \extas\interfaces\IItem $item
     *
     * @return bool
     */
    public function update($item): bool
    {
        if ($this->getPk() == '_id') {
            $item[$this->getPk()] = new \MongoId($item[$this->getPk()]);
        }

        return $this->collection->update([$this->getPk() => $item[$this->getPk()]], $item);
    }

    /**
     * @param array $query
     *
     * @return array|bool|mixed
     */
    public function deleteMany($query)
    {
        return $this->collection->remove($query);
    }

    /**
     * @param \jeyroik\extas\interfaces\systems\IItem $item
     *
     * @return bool
     */
    public function delete($item): bool
    {
        if ($this->getPk() == '_id') {
            $item[$this->getPk()] = new \MongoId($item[$this->getPk()]);
        }

        return $this->collection->remove([$this->getPk() => $item[$this->getPk()]]);
    }

    /**
     * @param string $groupBy
     * @param array|string $fields returning fields
     *
     * @return array
     */
    public function  group($groupBy, $fields)
    {
        $fieldsDecorated = [];

        if (is_array($fields)) {
            foreach ($fields as $field) {
                $fieldsDecorated[$field] = ['$push' => '$' . $field];
            }
        } else {
            $fieldsDecorated[$fields] = ['$push' => '$' . $fields];
        }

        $pipeline = [[
            '$group' => array_merge(
                ['_id' => '$' . $groupBy],
                $fieldsDecorated
            )
        ]];

        $rows = $this->collection->aggregate($pipeline, ['cursor' => true])['result'];

        /**
         * В качестве сахара, если в fields было передано 1 поле, то мы мапим groupBy к значениям из этого поля.
         * todo после перехода на 7.3 заменить на array_key_first($fieldsDecorated)
         */
        return count($fieldsDecorated) == 1
            ? array_column($rows, array_keys($fieldsDecorated)[0], '_id')
            : array_column($rows, null, '_id');
    }

    /**
     * @param $pk
     *
     * @return $this|IClientTable
     */
    public function setPk($pk)
    {
        $this->pk = $pk;

        return $this;
    }

    /**
     * @return string
     */
    public function getPk(): string
    {
        return $this->pk;
    }

    /**
     * @param $itemClass
     *
     * @return $this
     */
    public function setItemClass($itemClass)
    {
        $this->itemClass = $itemClass;

        return $this;
    }

    /**
     * @return string
     */
    public function getItemClass(): string
    {
        return $this->itemClass;
    }

    /**
     * @param string $fieldName
     *
     * @return $this
     */
    public function setIdAs($fieldName)
    {
        $this->idAs = $fieldName;

        return $this;
    }

    /**
     * @return string
     */
    public function getIdAs()
    {
        return $this->idAs;
    }
}
