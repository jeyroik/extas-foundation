<?php
namespace extas\components\repositories\drivers;

use extas\components\repositories\TLimitWithOffset;
use extas\components\TCompareValue;
use extas\components\THasConfig;
use extas\interfaces\IItem;
use extas\interfaces\repositories\drivers\IDriver;

class DriverFileJson extends Driver implements IDriver
{
    protected const FIELD__PATH = 'path';
    protected const FIELD__DB = 'db';
    protected const FIELD__TABLE = 'table';

    use TCompareValue;
    use TLimitWithOffset;
    use THasConfig;
    use THasConfig {
        THasConfig::__construct as baseConstruct;
    }

    protected array $data = [];
    protected string $hash = '';

    public function __construct(array $config)
    {
        $this->baseConstruct($config);

        $this->initData();
    }

    /**
     * @param IItem $item
     * @return void
     */
    public function insert($item)
    {
        if (!isset($this->data[$this->getTableName()])) {
            $this->data[$this->getTableName()] = [];
        }

        $this->data[$this->getTableName()][] = $item->__toArray();

        $this->saveData();

        return $item;
    }

    public function drop(): bool
    {
        $this->data[$this->getTableName()] = [];
        $this->saveData();

        return true;
    }

    public function deleteMany($query)
    {
        $deleted = 0;
        $data = $this->getTableData();

        foreach ($data as $index => $item) {
            if ($this->isQueryApplicableToItem($query, $item)) {
                unset($this->data[$this->getTableName()][$index]);
                $deleted++;
            }
        }

        $this->saveData();

        return $deleted;
    }

    /**
     * @param $item IItem
     *
     * @return bool|\Exception
     */
    public function delete($item): bool
    {
        $query = $item->__toArray();
        $data = $this->getTableData();

        foreach ($data as $index => $item) {
            if ($this->isQueryApplicableToItem($query, $item)) {
                unset($this->data[$this->getTableName()][$index]);
                $this->saveData();
                break;
            }
        }

        return true;
    }

    public function updateMany($query, $data)
    {
        $matched = 0;
        $table = $this->getTableData();
        $data = $this->dataToArray($data);

        foreach ($table as $index => $item) {
            if ($this->isQueryApplicableToItem($query, $item)) {
                $this->data[$this->getTableName()][$index] = array_merge($item, $data);
                $matched++;
            }
        }

        $this->saveData();

        return $matched;
    }

    protected function dataToArray($data): array
    {
        return is_object($data) ? $data->__toArray() : $data;
    }

    protected function isQueryApplicableToItem(array $query, $item): bool
    {
        $applicable = true;
        foreach ($query as $field => $value) {
            if (str_contains($field, '.')) {
                $subs = explode('.', $field);
                $localItem = $item;
                foreach ($subs as $sub) {
                    if (!isset($localItem[$sub])) {
                        $applicable = false;
                        break 2;
                    } else {
                        $localItem = $localItem[$sub];
                    }
                }
                if (!$this->compareValue($localItem, $value)) {
                    $applicable = false;
                    break;
                }
            } elseif (!isset($item[$field]) || !$this->compareValue($item[$field], $value)) {
                $applicable = false;
                break;
            }
        }

        return $applicable;
    }

    public function update($item): bool
    {
        $pk = $this->getPk();
        $data = $this->getTableData();

        foreach ($data as $index => $record) {
            if ($record[$pk] == $item[$pk]) {
                foreach($item as $field => $value) {
                    $record[$field] = $value;
                }
                $this->data[$this->getTableName()][$index] = $record;
                $this->saveData();
                return true;
            }
        }

        return false;
    }

    public function findOne(array $query = [], int $offset = 0, array $fields = [])
    {
        $found = 0;
        $data = $this->getTableData();

        foreach ($data as $item) {
            if ($this->isQueryApplicableToItem($query, $item)) {
                if ($found == $offset) {
                    return $this->allFilterFields($item, $fields);
                } else {
                    $found++;
                }
            }
        }

        return null;
    }

    public function findAll(array $query = [], int $limit = 0, int $offset = 0, array $orderBy = [], array $fields = [])
    {
        $matched = [];
        $data = $this->getTableData();

        foreach ($data as $item) {
            if ($this->isQueryApplicableToItem($query, $item)) {
                $matched[] = $this->allFilterFields($item, $fields);
            }
        }

        $matched = $this->allOrderBy($matched, $orderBy);
        $matched = $this->limit(
            $this->offset($matched, $offset),
            $limit
        );

        return $matched;
    }

    public function group(array $groupBy)
    {
        throw new \Exception('Method "group" is not implemented yet');
    }

    protected function allFilterFields(array $item, array $fields): array
    {
        if (!empty($fields)) {
            $short = [];
            foreach ($fields as $field) {
                $short[$field] = $item[$field] ?? null;
            }
            $item = $short;
        }

        return $item;
    }

    protected function allOrderBy(array $matched, array $orderBy): array
    {
        if (!empty($orderBy)) {
            $matched = array_column($matched, null, array_shift($orderBy));
            $asc = array_shift($orderBy);

            $asc ? ksort($matched) : krsort($matched);

            $matched = array_values($matched);
        }

        return $matched;
    }

    protected function getTableData(): array
    {
        return $this->data[$this->getTableName()] ?? [];
    }

    protected function saveData(): void
    {
        file_put_contents($this->path . $this->db, json_encode($this->data));
    }

    protected function initData(): void
    {
        $path = $this->path . $this->db;

        if (!is_file($path)) {
            file_put_contents($path, '[]');
        }

        $this->data = json_decode(file_get_contents($path), true);

        $this->hash = sha1(time()+mt_rand(0, 999999));
    }
}
