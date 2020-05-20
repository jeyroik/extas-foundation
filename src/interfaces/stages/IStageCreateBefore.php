<?php
namespace extas\interfaces\stages;

use extas\interfaces\IItem;

/**
 * Interface IStageCreateBefore
 *
 * @package extas\interfaces\stages
 * @author jeyroik <jeyroik@gmail.com>
 */
interface IStageCreateBefore
{
    /**
     * @param IItem $item
     */
    public function __invoke(IItem &$item): void;
}
