<?php
namespace extas\interfaces\parameters;

use extas\components\exceptions\MissedOrUnknown;
use extas\interfaces\collections\ICollection;
use extas\interfaces\IItem;

interface IParametredCollection extends IItem, ICollection
{
    public const SUBJECT = 'extas.parametred.collection';

    /**
     * Throw an error if item is missed.
     * 
     * @throws MissedOrUnknown
     */
    public function buildOne(string $name, bool $errorIfMissed = false): IParametred;

    /**
     * @return IParametred[]
     * @throws MissedOrUnknown
     */
    public function buildAll(array $names = [], bool $errorIfMissed = false): array;
}
