<?php
namespace extas\components\repositories;

use extas\interfaces\IHasClass;
use extas\interfaces\IItem;

/**
 * Class RepositoryClassObjects
 *
 * @package extas\components\repositories
 * @author Funcraft <me@funcraft.ru>
 */
class RepositoryClassObjects extends Repository
{
    /**
     * @param $where
     *
     * @return null|mixed
     */
    public function one($where)
    {
        /**
         * @var $model IHasClass|IItem
         */
        $model = parent::one($where);

        if ($model) {
            $className = $model->getClass();
            return new $className($model->__toArray());
        }

        return null;
    }

    /**
     * @param $where
     *
     * @return array
     */
    public function all($where)
    {
        /**
         * @var $models IHasClass[]|IItem[]
         */
        $models = parent::all($where);
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
