<?php
namespace extas\interfaces\stages;

use extas\interfaces\IItem;

/**
 * Interface IStageItemConvertTo
 *
 * @package extas\interfaces\stages
 * @author jeyroik@gmail.com
 */
interface IStageItemConvertTo
{
    public const NAME__SUFFIX_INTEGER = 'to.int';
    public const NAME__SUFFIX_STRING = 'to.string';
    public const NAME__SUFFIX_ARRAY = 'to.array';
    public const NAME__SUFFIX_JSON = 'to.json';

    /**
     * @param IItem $item
     * @param mixed $result current conversion result
     */
    public function __invoke(IItem &$item, &$result): void;
}
