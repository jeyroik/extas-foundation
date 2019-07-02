<?php
namespace extas\components\repositories;

use extas\components\repositories\clients\databases\DbCurrent;
use extas\interfaces\repositories\IRepository;
use extas\interfaces\repositories\clients\IClientTable;
use extas\components\Item;

/**
 * Class Repository
 *
 * @package df\components
 * @author aivanov@fix.ru
 */
class Repository extends Item implements IRepository
{
    protected $repoSubject = 'df.repo';

    /**
     * @var IClientTable
     */
    protected $table = null;

    protected $name = '';
    protected $scope = 'df';
    protected $pk = '_id';
    protected $itemClass = \df\components\Item::class;
    protected $idAs = '';

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

        $this->table = DbCurrent::getTable($this->getName(), $this->getScope());
        $this->table->setPk($this->pk);
        $this->table->setItemClass($this->itemClass);
        $this->table->setIdAs($this->idAs);
    }

    /**
     * @param $where
     *
     * @return mixed
     */
    public function one($where)
    {
        return $this->findAfter('findOne', $where);
    }

    /**
     * @param $where
     *
     * @return array
     */
    public function all($where)
    {
        return $this->findAfter('findAll', $where);
    }

    /**
     * @param $item
     *
     * @return mixed
     * @throws
     */
    public function create($item)
    {
        foreach ($this->getPluginsByStage('df.' . $this->getName() . '.create.before') as $plugin) {
            $plugin($item);
        }

        $result = $this->getRepoInstance()->insert($item);

        foreach ($this->getPluginsByStage('df.' . $this->getName() . '.create.after') as $plugin) {
            $plugin($result, $item);
        }

        return $result;
    }

    /**
     * @param $item
     * @param $where
     *
     * @return int
     * @throws
     */
    public function update($item, $where = []): int
    {
        foreach ($this->getPluginsByStage('df.' . $this->getName() . '.update.before') as $plugin) {
            $plugin($item, $where);
        }

        $repo = $this->getRepoInstance();
        $result = empty($where) ? $repo->update($item) : $repo->updateMany($where, $item);

        foreach ($this->getPluginsByStage('df.' . $this->getName() . '.update.after') as $plugin) {
            $plugin($result, $where, $item);
        }

        return $result;
    }

    /**
     * @param $where
     * @param mixed $item
     *
     * @return int
     * @throws
     */
    public function delete($where, $item = null): int
    {
        foreach ($this->getPluginsByStage('df.' . $this->getName() . '.delete.before') as $plugin) {
            $plugin($item, $where);
        }

        $repo = $this->getRepoInstance();
        $result = empty($where) ? $repo->delete($item) : $repo->deleteMany($where);

        foreach ($this->getPluginsByStage('df.' . $this->getName() . '.delete.after') as $plugin) {
            $plugin($result, $where, $item);
        }

        return $result;
    }

    /**
     * @return string
     */
    protected function getName(): string
    {
        return $this->name;
    }

    /**
     * @return string
     */
    protected function getScope()
    {
        return $this->scope;
    }

    /**
     * @param $method string Method name
     * @param $where
     *
     * @return mixed
     * @throws
     */
    protected function findAfter($method, $where)
    {
        $result = $this->getRepoInstance()->$method($where);

        foreach ($this->getPluginsByStage('df.' . $this->getName() . '.find.after') as $plugin) {
            $plugin($result);
        }

        return $result;
    }

    /**
     * @return IClientTable
     * @throws \Exception
     */
    protected function getRepoInstance()
    {
        if (!$this->table) {
            $this->table = DbCurrent::getTable($this->getName(), $this->getScope());
        }

        return $this->table;
    }

    /**
     * @param $repo IClientTable
     *
     * @return $this
     */
    protected function setRepoInstance($repo)
    {
        $this->table = $repo;

        return $this;
    }

    /**
     * @return string
     */
    protected function getSubjectForExtension(): string
    {
        return $this->repoSubject;
    }
}
