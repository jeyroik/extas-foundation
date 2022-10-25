<?php
namespace extas\components\repositories\drivers;

use extas\components\THasConfig;
use extas\interfaces\IItem;
use extas\interfaces\repositories\drivers\IDriver;

class DriverFileJson extends Driver implements IDriver
{
    protected const FIELD__PATH = 'path';
    protected const FIELD__DB = 'db';
    protected const FIELD__TABLE = 'table';

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
            $applicable = true;
            foreach ($query as $field => $value) {
                if (!isset($item[$field])) {
                    $applicable = false;
                    break;
                }

                $applicable = $this->compareValue($item[$field], $value);
                
                if (!$applicable) {
                    break;
                }
            }

            if ($applicable) {
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
            $applicable = true;
            foreach ($query as $field => $value) {
                if (!isset($item[$field])) {
                    $applicable = false;
                    break;
                }
                $applicable = $this->compareValue($item[$field], $value);
                
                if (!$applicable) {
                    break;
                }
            }

            if ($applicable) {
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

        foreach ($table as $index => $item) {
            $applicable = true;
            foreach ($query as $field => $value) {
                if (!isset($item[$field])) {
                    $applicable = false;
                    break;
                }
                $applicable = $this->compareValue($item[$field], $value);
                
                if (!$applicable) {
                    break;
                }
            }

            if ($applicable) {
                foreach ($data as $field => $value) {
                    $item[$field] = $value;
                }
                $this->data[$this->getTableName()][$index] = $item;
                $matched++;
            }
        }

        $this->saveData();

        return $matched;
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
            $applicable = true;
            foreach ($query as $field => $value) {
                if (!isset($item[$field])) {
                    $applicable = false;
                    break;
                }
                $applicable = $this->compareValue($item[$field], $value);
                
                if (!$applicable) {
                    break;
                }
            }

            if ($applicable) {
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
            $applicable = true;
            foreach ($query as $field => $value) {
                if (!isset($item[$field])) {
                    $applicable = false;
                    break;
                }
                $applicable = $this->compareValue($item[$field], $value);
                
                if (!$applicable) {
                    break;
                }
            }

            if ($applicable) {
                $matched[] = $this->allFilterFields($item, $fields);
            }
        }

        $matched = $this->allOrderBy($matched, $orderBy);
        $matched = $this->allLimitWithOffset($matched, $limit, $offset);

        return $matched;
    }

    public function group(array $groupBy)
    {
        throw new \Exception('Method "group" is not implemented yet');
    }

    protected function compareValue($source, $compareTo): bool
    {
        $checkers = [
            'isEqualBasic',
            'isEqualIn'
        ];

        $applicable = true;

        foreach ($checkers as $method) {
            $applicable = $this->$method($source, $compareTo);
            if ($applicable) {
                break;
            }
        }

        return $applicable;
    }

    protected function isEqualBasic($source, $compareTo): bool
    {
        if (is_array($source)) {
            foreach ($source as $value) {
                if ($value == $compareTo) {
                    return true;
                }
            }

            return false;
        }
        return $source == $compareTo;
    }

    protected function isEqualIn($source, $compareTo): bool
    {
        if (is_array($compareTo)) {
            foreach ($compareTo as $value) {
                if (is_array($source)) {
                    $equal = $this->isEqualBasic($source, $value);
                    if ($equal) {
                        return true;
                    }
                }
                elseif ($source == $value) {
                    return true;
                }
            }
        }

        return false;
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

    protected function allLimitWithOffset($matched, $limit, $offset): array
    {
        if ($limit) {
            $limited = [];
            foreach ($matched as $index => $item) {
                if ($offset && ($index < $offset)) {
                    continue;
                }
                if (count($limited) == $limit) {
                    break;
                }
                $limited[] = $item;
            }

            $matched = $limited;
        } elseif ($offset) {
            $matchedWithOffset = [];
            foreach ($matched as $index => $item) {
                if ($index < $offset) {
                    continue;
                }
                $matchedWithOffset[] = $item;
            }

            $matched = $matchedWithOffset;
        }

        return $matched;
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
