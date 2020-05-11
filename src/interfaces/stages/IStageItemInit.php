<?php
namespace extas\interfaces\stages;

use extas\interfaces\IItem;

/**
 * Interface IStageItemInit
 *
 * @package extas\interfaces\stages
 * @author jeyroik@gmail.com
 */
interface IStageItemInit
{
    public const NAME__SUFFIX = 'init';

    /**
     * @param IItem $item
     */
    public function __invoke(IItem &$item): void;
}
