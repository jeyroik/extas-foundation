<?php
namespace extas\interfaces\stages;

use extas\interfaces\IItem;
use extas\interfaces\repositories\IRepository;

/**
 * Interface IStageItemCreated
 *
 * @package extas\interfaces\stages
 * @author jeyroik@gmail.com
 */
interface IStageItemCreated
{
    public const NAME__SUFFIX = 'created';

    /**
     * @param IItem $item
     * @param IItem $itemCreated
     * @param IRepository $repository
     */
    public function __invoke(IItem &$item, IItem &$itemCreated, IRepository $repository): void;
}
