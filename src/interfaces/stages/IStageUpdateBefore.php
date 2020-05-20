<?php
namespace extas\interfaces\stages;

use extas\interfaces\IItem;
use extas\interfaces\repositories\IRepository;

/**
 * Interface IStageUpdateBefore
 *
 * @package extas\interfaces\stages
 * @author jeyroik <jeyroik@gmail.com>
 */
interface IStageUpdateBefore
{
    /**
     * @param IItem $item
     * @param array $where
     * @param IRepository $itemRepository
     */
    public function __invoke(IItem &$item, array &$where, IRepository $itemRepository): void;
}
