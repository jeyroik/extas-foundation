<?php
namespace extas\components\repositories\drivers;

use extas\components\basics\THasConfig;
use extas\interfaces\IItem;
use extas\interfaces\repositories\clients\IClientTable;

class DriverFileJson// implements IClientTable
{
    protected const FIELD__PATH = 'path';
    protected const FIELD__DB = 'db';
    protected const FIELD__ITEM_CLASS = 'item_class';

    use THasConfig;
    use THasConfig {
        THasConfig::__construct as baseConstruct;
    }

    protected array $data = [];
    protected string $hash = '';

    public function drop(): bool
    {
        $this->data = [];

        $this->saveData();

        return true;
    }

    public function deleteMany($query)
    {
        return 0;
    }

    public function delete($item): bool
    {
        return false;
    }

    public function updateMany($query, $data)
    {
        
    }

    public function update($item): bool
    {
        return false;
    }

    public function findOne(array $query = [], int $offset = 0, array $fields = [])
    {
        return [];
    }

    public function findAll(array $query = [], int $limit = 0, int $offset = 0, array $orderBy = [], array $fields = [])
    {
        $matched = [];

        foreach ($this->data as $item) {
            $applicable = true;
            foreach ($query as $field => $value) {
                if ($item[$field] != $value) {
                    $applicable = false;
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

    /**
     * @param IItem $item
     * @return void
     */
    public function insert($item)
    {
        $this->data[] = $item->__toArray();

        $this->saveData();

        return $item;
    }

    public function __construct(array $config)
    {
        $this->baseConstruct($config);

        $this->initData();
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
