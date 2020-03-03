<?php
namespace extas\interfaces\repositories\clients;

/**
 * Interface IClientDatabase
 *
 * @package extas\interfaces\repositories\clients
 * @author jeyroik@gmail.com
 */
interface IClientDatabase
{
    /**
     * @param string $tableName
     *
     * @return IClientTable
     */
    public function getTable(string $tableName): IClientTable;
}
