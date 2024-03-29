<?php
namespace tests\tmp_install;

use extas\components\repositories\Repository;

class RepositoryEntries extends Repository
{
    protected $table = null;
    protected string $name = 'entries';
    protected string $scope = 'extas';
    protected string $pk = 'name';
    protected string $itemClass = '\extas\components\plugins\Plugin';
    protected string $repoSubject = 'entries';

    /**
     * Repository constructor.
     *
     * @param array $config
     *
     * @throws
     */
    public function __construct(array $config = [])
    {
        parent::__construct($config);

        $this->table = $this->getRepoInstance();
    }

    /**
     * @param $where
     * @param int $offset
     * @param array $fields
     * @return IItem|mixed|null
     * @throws \Exception
     */
    public function one($where, int $offset = 0, array $fields = [])
    {
        
        
        $result = $this->getRepoInstance()->findOne($where, $offset, $fields);

        if ($result) {
            $itemClass = $this->itemClass;
            $result = new $itemClass($result);
        }
        
        

        return $result;
    }

    /**
     * @param $where
     * @param int $limit
     * @param int $offset
     * @param array $orderBy
     * @param array $fields
     * @return array|IItem[]
     * @throws \Exception
     */
    public function all($where, int $limit = 0, int $offset = 0, array $orderBy = [], array $fields = [])
    {
        
        
        $result = $this->getRepoInstance()->findAll($where, $limit, $offset, $orderBy, $fields);
        

        $itemClass = $this->itemClass;

        foreach($result as $index => $item) {
            $result[$index] = new $itemClass($item);
        }

        

        return $result;
    }

    /**
     * @param $item
     * @return IItem
     * @throws \Exception
     */
    public function create($item)
    {
        
        
        $result = $this->getRepoInstance()->insert($item);
        
        

        return $result;
    }

    /**
     * @param $item
     * @param array $where
     * @return int
     * @throws \Exception
     */
    public function update($item, $where = []): int
    {
        
        
        $repo = $this->getRepoInstance();
        $result = empty($where) ? $repo->update($item) : $repo->updateMany($where, $item);
        
        

        return $result;
    }

    /**
     * @param $where
     * @param null $item
     * @return int
     * @throws \Exception
     */
    public function delete($where, $item = null): int
    {
        
        
        $repo = $this->getRepoInstance();
        $result = empty($where) ? $repo->delete($item) : $repo->deleteMany($where);
        
        

        return $result;
    }

    /**
     * @return bool
     * @throws \Exception
     */
    public function drop(): bool
    {
        
        
        $repo = $this->getRepoInstance();
        $result = $repo->drop();
        
        

        return $result;
    }

    /**
     * @return IClientTable
     * @throws \Exception
     */
    protected function getRepoInstance()
    {
        if (!$this->table) {
            $this->table = new \extas\components\repositories\drivers\DriverFileJson([
                'table' => $this, 'path' => 'tests/tmp/', 'db' => 'system', 
            ]);
        }

        return $this->table;
    }
}
