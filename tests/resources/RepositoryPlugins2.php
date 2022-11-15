<?php
namespace tests\tmp;

use extas\components\repositories\Repository;

class RepositoryPlugins2 extends Repository
{
    protected $table = null;
    protected string $name = 'plugins2';
    protected string $scope = 'extas';
    protected string $pk = 'name';
    protected string $itemClass = '\extas\components\plugins\Plugin';
    protected string $repoSubject = 'plugins2';

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
        //one-before-code
        foreach($this->getPluginsByStage('plugins2.one.before') as $plugin) {
            $plugin($where, $offset, $fields);
        }

        $result = $this->getRepoInstance()->findOne($where, $offset, $fields);

        if ($result) {
            $itemClass = $this->itemClass;
            $result = new $itemClass($result);
        }
        
        foreach($this->getPluginsByStage('plugins2.one.after') as $plugin) {
            $plugin($result);
        }
        //one-after-code

        return $result;
    }

    /**
     * @param $where
     * @param int $offset
     * @param array $fields
     * @return IItem|mixed|null
     * @throws \Exception
     */
    public function oneAsArray($where, int $offset = 0, array $fields = [])
    {
        //one-as-array-before-code
        foreach($this->getPluginsByStage('plugins2.one.as.array.before') as $plugin) {
            $plugin($where, $offset, $fields);
        }

        $result = $this->getRepoInstance()->findOne($where, $offset, $fields);
        if (!$result) {
            $result = [];
        }
        
        foreach($this->getPluginsByStage('plugins2.one.as.array.after') as $plugin) {
            $plugin($result);
        }
        //one-as-array-after-code

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
        //all-before-code
        foreach($this->getPluginsByStage('plugins2.all.before') as $plugin) {
            $plugin($where, $offset, $fields);
        }

        $result = $this->getRepoInstance()->findAll($where, $limit, $offset, $orderBy, $fields);
        
        foreach($this->getPluginsByStage('plugins2.all.after') as $plugin) {
            $plugin($result);
        }

        $itemClass = $this->itemClass;

        foreach($result as $index => $item) {
            $result[$index] = new $itemClass($item);
        }

        //all-after-code

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
    public function allAsArray($where, int $limit = 0, int $offset = 0, array $orderBy = [], array $fields = [])
    {
        //all-as-array-before-code
        foreach($this->getPluginsByStage('plugins2.all.as.array.before') as $plugin) {
            $plugin($where, $offset, $fields);
        }

        $result = $this->getRepoInstance()->findAll($where, $limit, $offset, $orderBy, $fields);
        
        foreach($this->getPluginsByStage('plugins2.all.as.array.after') as $plugin) {
            $plugin($result);
        }
        //all-as-array-after-code

        return $result;
    }

    /**
     * @param $item
     * @return IItem
     * @throws \Exception
     */
    public function create($item)
    {
        //create-before-code
        foreach($this->getPluginsByStage('plugins2.create.before') as $plugin) {
            $plugin($item, $this);
        }

        $result = $this->getRepoInstance()->insert($item);
        
        foreach($this->getPluginsByStage('plugins2.create.after') as $plugin) {
            $plugin($result, $item, $this);
        }
        //create-after-code

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
        //update-before-code
        foreach($this->getPluginsByStage('plugins2.update.before') as $plugin) {
            $plugin($item, $where, $this);
        }

        $repo = $this->getRepoInstance();
        $result = empty($where) ? $repo->update($item) : $repo->updateMany($where, $item);
        
        foreach($this->getPluginsByStage('plugins2.update.after') as $plugin) {
            $plugin($result, $where, $item, $this);
        }
        //update-after-code

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
        //delete-before-code
        foreach($this->getPluginsByStage('plugins2.delete.before') as $plugin) {
            $plugin($item, $where, $this);
        }

        $repo = $this->getRepoInstance();
        $result = empty($where) ? $repo->delete($item) : $repo->deleteMany($where);
        
        foreach($this->getPluginsByStage('plugins2.delete.after') as $plugin) {
            $plugin($result, $where, $item, $this);
        }
        //delete-after-code

        return $result;
    }

    /**
     * @return bool
     * @throws \Exception
     */
    public function drop(): bool
    {
        //drop-before-code is included from drop-before-code.php
        foreach($this->getPluginsByStage('plugins2.drop.before') as $plugin) {
            $plugin($this);
        }

        $repo = $this->getRepoInstance();
        $result = $repo->drop();
        
        foreach($this->getPluginsByStage('plugins2.drop.after') as $plugin) {
            $plugin($result);
        }
        \extas\components\Plugins::reset();

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
                'table' => $this, 'path' => 'configs/', 'db' => 'system', 
            ]);
        }

        return $this->table;
    }
}
