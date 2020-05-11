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
     * IItem constructor.
     * @param array $config
     */
    public function __construct(array $config = []);

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
     * @return string
     */
    public function __toJson(): string;

    /**
     * @param IItem $other
     * @return bool
     */
    public function __equal(IItem $other): bool;

    /**
     * @param array $data
     * @return $this
     */
    public function __merge(array $data);

    /**
     * @param $item
     * @param $repo IRepository
     *
     * @return $this
     */
    public function __created($item, $repo);

    /**
     * @return string
     */
    public function __subject(): string;

    /**
     * @param string ...$params
     * @return bool
     */
    public function has(...$params): bool;
}
