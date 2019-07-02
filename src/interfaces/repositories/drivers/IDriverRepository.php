<?php
namespace extas\interfaces\repositories\drivers;

/**
 * Interface IDriverRepository
 *
 * @package extas\interfaces\repositories\drivers
 * @author aivanov@fix.ru
 */
interface IDriverRepository
{
    /**
     * IDriverRepository constructor.
     *
     * @param array $config
     */
    public function __construct($config = []);

    /**
     * @param $where
     *
     * @return IDriver|null
     */
    public function findOne($where);

    /**
     * @return IDriver[]
     */
    public function findAll();
}
