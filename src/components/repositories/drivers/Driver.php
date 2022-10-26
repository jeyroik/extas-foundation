<?php
namespace extas\components\repositories\drivers;

use extas\interfaces\repositories\drivers\IDriver;

/**
 * Class ClientTableAbstract
 *
 * @package extas\components\repositories\clients
 * @author jeyroik@gmail.com
 */
abstract class Driver implements IDriver
{
    /**
     * @return string
     */
    public function getPk(): string
    {
        return $this->table->getPk();
    }

    /**
     * @return string
     */
    public function getItemClass(): string
    {
        return $this->table->getItemClass();
    }

    public function getTableName(): string
    {
        return $this->table->getName();
    }
}
