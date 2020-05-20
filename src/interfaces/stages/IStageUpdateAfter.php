<?php
namespace extas\interfaces\stages;

use extas\interfaces\IItem;
use extas\interfaces\repositories\IRepository;

/**
 * Interface IStageUpdateAfter
 *
 * @package extas\interfaces\stages
 * @author jeyroik <jeyroik@gmail.com>
 */
interface IStageUpdateAfter
{
    /**
     * @param bool $result
     * @param IItem $item
     * @param array $where
     * @param IRepository $itemRepository
     */
    public function __invoke(bool &$result, array $where, IItem $item, IRepository $itemRepository): void;
}
