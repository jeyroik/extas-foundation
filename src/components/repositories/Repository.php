<?php
namespace extas\components\repositories;

use extas\components\plugins\TPluginAcceptable;
use extas\components\THasConfig;
use extas\components\TAsArray;
use extas\components\THasOutput;
use extas\interfaces\repositories\IRepository;

/**
 * Class Repository
 *
 * @package extas\components
 * @author jeyroik@gmail.com
 */
abstract class Repository implements IRepository
{
    use TAsArray;
    use THasConfig;
    use TPluginAcceptable;
    use THasOutput;

    protected string $name = '';
    protected string $scope = 'extas';
    protected string $pk = '_id';
    protected string $itemClass = Item::class;
    protected string $repoSubject = 'extas.repo';

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
