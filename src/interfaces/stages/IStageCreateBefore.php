<?php
namespace extas\interfaces\stages;

use extas\interfaces\IItem;
use extas\interfaces\repositories\IRepository;

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
     * @param IRepository $repository
     */
    public function __invoke(IItem &$item, IRepository $repository = null): void;
}
