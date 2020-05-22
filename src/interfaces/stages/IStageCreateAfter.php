<?php
namespace extas\interfaces\stages;

use extas\interfaces\IItem;
use extas\interfaces\repositories\IRepository;

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
     * @param IRepository $repository
     */
    public function __invoke(IItem &$createdItem, IItem $sourceItem, IRepository $repository = null): void;
}
