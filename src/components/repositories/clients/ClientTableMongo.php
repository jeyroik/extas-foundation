<?php
namespace extas\components\repositories\clients;

use extas\interfaces\repositories\clients\IClientTable;

/**
 * Class ClientTableMongo
 *
 * @package extas\components\repositories\clients
 * @author jeyroik@gmail.com
 */
class ClientTableMongo extends ClientTableAbstract implements IClientTable
{
    /**
     * @var \MongoCollection
     */
    protected $collection = null;

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
     * @return array|\extas\interfaces\IItem|null
     */
    public function findOne($query = [])
    {
        $this->prepareQuery($query);
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
     * @return array|\extas\interfaces\IItem[]
     */
    public function findAll($query = [])
    {
        $this->prepareQuery($query);
        $itemClass = $this->getItemClass();
        $recordsCursor = $this->collection->find($query);
        $records = [];

        while ($recordsCursor->hasNext()) {
            $record = $recordsCursor->getNext();
            $record['_id'] = (string) $record['_id'];
            $records[] = new $itemClass($record);
        }

        return $records;
    }

    /**
     * @param \extas\interfaces\IItem $item
     *
     * @return \Exception|\extas\interfaces\IItem|mixed
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
        $result = $this->collection->update($query, $data);

        if (is_bool($result)) {
            return (int) $result;
        }

        return isset($result['n']) ? (int) $result['n'] : 0;
    }

    /**
     * @param \extas\interfaces\IItem $item
     *
     * @return bool
     */
    public function update($item): bool
    {
        $pk = $this->getPk() == '_id' ? new \MongoId($item[$this->getPk()]) : $item[$this->getPk()];
        if (isset($item['_id'])) {
            unset($item['_id']);
        }

        $result = $this->collection->update([$this->getPk() => $pk], $item->__toArray());

        return is_bool($result)
            ? $result
            : (isset($result['n']) && ($result['n'] >= 1)
                ? true
                : false);
    }

    /**
     * @param array $query
     *
     * @return array|bool|mixed
     */
    public function deleteMany($query)
    {
        $result = $this->collection->remove($query);

        if (is_bool($result)) {
            return (int) $result;
        }

        return isset($result['n']) ? (int) $result['n'] : 0;
    }

    /**
     * @param \extas\interfaces\IItem $item
     *
     * @return bool
     */
    public function delete($item): bool
    {
        if ($this->getPk() == '_id') {
            $item[$this->getPk()] = new \MongoId($item[$this->getPk()]);
        }

        $result = $this->collection->remove([$this->getPk() => $item[$this->getPk()]]);

        return is_bool($result)
            ? $result
            : (isset($result['n']) && ($result['n'] >= 1)
                ? true
                : false);
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
     * @param array $query
     */
    protected function prepareQuery(&$query)
    {
        foreach ($query as $fieldName => $fieldValue) {
            if (is_array($fieldValue)) {
                $query[$fieldName] = ['$in' => $fieldValue];
            }
        }
    }
}
