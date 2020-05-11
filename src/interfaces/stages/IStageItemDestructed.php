<?php
namespace extas\interfaces\stages;

use extas\interfaces\IItem;

/**
 * Interface IStageItemDestructed
 *
 * @package extas\interfaces\stages
 * @author jeyroik@gmail.com
 */
interface IStageItemDestructed
{
    public const NAME__SUFFIX = 'after';

    /**
     * @param IItem $item
     */
    public function __invoke(IItem &$item): void;
}
