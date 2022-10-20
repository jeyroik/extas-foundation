<?php
namespace extas\components\repositories;

use extas\interfaces\repositories\IRepository;
use extas\components\Item;

/**
 * Class Repository
 *
 * @package extas\components
 * @author jeyroik@gmail.com
 */
abstract class Repository extends Item implements IRepository
{
    protected string $name = '';
    protected string $scope = 'extas';
    protected string $pk = '_id';
    protected string $itemClass = Item::class;
    protected string $repoSubject = 'extas.repo';

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
    }

    /**
     * @param array $byFields
     * @return $this|IRepository
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
     * @param $repo
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
     * @return string
     */
    protected function getBaseStageName($stage)
    {
        return $this->getSubjectForExtension() . '.' . $this->getName() . '.' . $stage;
    }
}
