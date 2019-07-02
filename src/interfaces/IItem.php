<?php
namespace extas\interfaces;

use extas\interfaces\extensions\IExtendable;
use extas\interfaces\plugins\IPluginsAcceptable;
use extas\interfaces\repositories\IRepository;

/**
 * Interface IItem
 *
 * @package extas\interfaces
 * @author jeyroik@gmail.com
 */
interface IItem extends \ArrayAccess, \Iterator, IPluginsAcceptable, IExtendable
{
    /**
     * @param string $format
     *
     * @return string|int
     */
    public function getCreatedAt($format = '');

    /**
     * @param string $format
     *
     * @return string|int
     */
    public function getUpdatedAt($format = '');

    /**
     * @return array
     */
    public function __toArray(): array;

    /**
     * @return string
     */
    public function __toString(): string;

    /**
     * @return int
     */
    public function __toInt(): int;

    /**
     * @param $item
     * @param $repo IRepository
     *
     * @return $this
     */
    public function __created($item, $repo);
}
