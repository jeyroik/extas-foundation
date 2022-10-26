<?php
namespace extas\interfaces\stages;

use extas\interfaces\IItem;

interface IStageItemEqual
{
    public const NAME = 'equal';

    public function __invoke(IItem $source, IItem $compareTo, bool &$equal): void;
}
