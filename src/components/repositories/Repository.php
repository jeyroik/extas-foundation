<?php
namespace extas\components\repositories;

use extas\components\repositories\clients\databases\DbCurrent;
use extas\interfaces\IItem;
use extas\interfaces\repositories\IRepository;
use extas\interfaces\repositories\clients\IClientTable;
use extas\components\Item;

/**
 * Class Repository
 *
 * @package extas\components
 * @author jeyroik@gmail.com
 */
class Repository extends Item implements IRepository
{
    protected string $repoSubject = 'extas.repo';

    /**
     * @var IClientTable
     */
    protected ?IClientTable $table = null;

    protected string $name = '';
    protected string $scope = 'extas';
    protected string $pk = '_id';
    protected string $itemClass = Item::class;

    /**
     * Stages constraints
     */
    protected bool $isAllowFindAfterStage = true;
    protected bool $isAllowCreateBeforeStage = true;
    protected bool $isAllowCreateAfterStage = true;
    protected bool $isAllowUpdateBeforeStage = true;
    protected bool $isAllowUpdateAfterStage = true;
    protected bool $isAllowDeleteBeforeStage = true;
    protected bool $isAllowDeleteAfterStage = true;
    protected bool $isAllowDropBeforeStage = true;
    protected bool $isAllowDropAfterStage = true;

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
        $this->findAfter('findOne', $result);

        return $result;
    }

    /**
     * @param $where
     * @param int $limit
     * @param int $offset
     * @param array $orderBy
     * @param array $fields
     * @return IItem[]
     * @throws \Exception
     */
    public function all($where, int $limit = 0, int $offset = 0, array $orderBy = [], array $fields = [])
    {
        $result = $this->getRepoInstance()->findAll($where, $limit, $offset, $orderBy, $fields);
        $this->findAfter('findAll', $result);

        return $result;
    }

    /**
     * @param $item
     *
     * @return mixed
     * @throws
     */
    public function create($item)
    {
        if ($this->isAllowCreateBeforeStage) {
            foreach ($this->getPluginsByStage('extas.' . $this->getName() . '.create.before') as $plugin) {
                $plugin($item);
            }
        }

        $result = $this->getRepoInstance()->insert($item);

        if ($this->isAllowCreateAfterStage) {
            foreach ($this->getPluginsByStage('extas.' . $this->getName() . '.create.after') as $plugin) {
                $plugin($result, $item);
            }
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
        if ($this->isAllowUpdateBeforeStage) {
            foreach ($this->getPluginsByStage('extas.' . $this->getName() . '.update.before') as $plugin) {
                $plugin($item, $where);
            }
        }

        $repo = $this->getRepoInstance();
        $result = empty($where) ? $repo->update($item) : $repo->updateMany($where, $item);

        if ($this->isAllowUpdateAfterStage) {
            foreach ($this->getPluginsByStage('extas.' . $this->getName() . '.update.after') as $plugin) {
                $plugin($result, $where, $item);
            }
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
        if ($this->isAllowDeleteBeforeStage) {
            foreach ($this->getPluginsByStage('extas.' . $this->getName() . '.delete.before') as $plugin) {
                $plugin($item, $where);
            }
        }

        $repo = $this->getRepoInstance();
        $result = empty($where) ? $repo->delete($item) : $repo->deleteMany($where);

        if ($this->isAllowDeleteAfterStage) {
            foreach ($this->getPluginsByStage('extas.' . $this->getName() . '.delete.after') as $plugin) {
                $plugin($result, $where, $item);
            }
        }

        return $result;
    }

    /**
     * @param array $byFields
     *
     * @return $this
     * @throws \Exception
     */
    public function group(array $byFields): IRepository
    {
        $repo = $this->getRepoInstance();
        $repo->group($byFields);
        $this->setRepoInstance($repo);

        return $this;
    }

    /**
     * @return bool
     * @throws \Exception
     */
    public function drop(): bool
    {
        if ($this->isAllowDropBeforeStage) {
            foreach ($this->getPluginsByStage('extas.' . $this->getName() . '.drop.before') as $plugin) {
                $plugin($this);
            }
        }

        $repo = $this->getRepoInstance();
        $result = $repo->drop();

        if ($this->isAllowDropAfterStage) {
            foreach ($this->getPluginsByStage('extas.' . $this->getName() . '.drop.after') as $plugin) {
                $plugin($result);
            }
        }

        return $result;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getScope(): string
    {
        return $this->scope;
    }

    /**
     * @return string
     */
    public function getPk(): string
    {
        return $this->pk;
    }

    /**
     * @return string
     */
    public function getItemClass(): string
    {
        return $this->itemClass;
    }

    /**
     * @param $method string Method name
     * @param $result
     *
     * @return void
     * @throws
     */
    protected function findAfter($method, &$result): void
    {
        if ($this->isAllowFindAfterStage) {
            foreach ($this->getPluginsByStage('extas.' . $this->getName() . '.find.after') as $plugin) {
                $plugin($result, $method);
            }
        }
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

    /**
     * @param $stage
     *
     * @return string
     */
    protected function getBaseStageName($stage)
    {
        return $this->getSubjectForExtension() . '.' . $this->getName() . '.' . $stage;
    }
}
