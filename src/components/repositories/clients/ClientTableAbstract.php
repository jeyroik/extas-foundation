<?php
namespace extas\components\repositories\clients;

use extas\interfaces\repositories\clients\IClientTable;

/**
 * Class ClientTableAbstract
 *
 * @package extas\components\repositories\clients
 * @author jeyroik@gmail.com
 */
abstract class ClientTableAbstract implements IClientTable
{
    /**
     * @var string
     */
    protected string $pk = '_id';

    /**
     * @var string
     */
    protected string $itemClass = '';

    /**
     * @return bool
     */
    public function drop(): bool
    {
        echo 'Not implemented yet';

        return false;
    }

    /**
     * @param $pk
     *
     * @return $this|IClientTable
     */
    public function setPk($pk)
    {
        $this->pk = $pk;

        return $this;
    }

    /**
     * @return string
     */
    public function getPk(): string
    {
        return $this->pk;
    }

    /**
     * @param $itemClass
     *
     * @return $this
     */
    public function setItemClass($itemClass)
    {
        $this->itemClass = $itemClass;

        return $this;
    }

    /**
     * @return string
     */
    public function getItemClass(): string
    {
        return $this->itemClass;
    }
}
