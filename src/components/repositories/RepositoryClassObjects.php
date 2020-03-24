<?php
namespace extas\components\repositories;

use extas\interfaces\IHasClass;
use extas\interfaces\IItem;

/**
 * Class RepositoryClassObjects
 *
 * @deprecated переписать на использование IHasClass.
 * @package extas\components\repositories
 * @author jeyroik@gmail.com
 */
class RepositoryClassObjects extends Repository
{
    /**
     * @param $where
     * @param int $offset
     * @param array $fields
     * @return IItem|mixed|null
     * @throws \Exception
     */
    public function one($where, int $offset = 0, array $fields = [])
    {
        /**
         * @var $model IHasClass|IItem
         */
        $model = parent::one($where, $offset, $fields);

        if ($model) {
            $className = $model->getClass();
            return new $className($model->__toArray());
        }

        return null;
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
        /**
         * @var $models IHasClass[]|IItem[]
         */
        $models = parent::all($where, $limit, $offset, $orderBy, $fields);
        $real = [];

        if (!empty($models)) {
            foreach ($models as $model) {
                $className = $model->getClass();
                $real[] = new $className($model->__toArray());
            }
        }

        return $real;
    }
}
