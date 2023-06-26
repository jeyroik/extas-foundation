<?php
namespace extas\interfaces\parameters;

use extas\components\exceptions\MissedOrUnknown;
use extas\interfaces\collections\ICollection;
use extas\interfaces\IItem;

interface IParams extends IItem, ICollection
{
    public const SUBJECT = 'extas.params';

    /**
     * Throw an error if item is missed and $errorOnMissed is true.
     * 
     * @throws MissedOrUnknown
     */
    public function buildOne(string $name, bool $errorIfMissed = false): IParam;

    /**
     * Throw an error if item is missed and $errorOnMissed is true.
     * 
     * @return IParam[]
     * @throws MissedOrUnknown
     */
    public function buildAll(array $names = [], bool $errorIfMissed = false): array;
}
