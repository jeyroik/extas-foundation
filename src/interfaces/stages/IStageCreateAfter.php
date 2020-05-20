<?php
namespace extas\interfaces\stages;

use extas\interfaces\IItem;

/**
 * Interface IStageCreateAfter
 *
 * @package extas\interfaces\stages
 * @author jeyroik <jeyroik@gmail.com>
 */
interface IStageCreateAfter
{
    /**
     * @param IItem $createdItem
     * @param IItem $sourceItem
     */
    public function __invoke(IItem &$createdItem, IItem $sourceItem): void;
}
