<?php
namespace tests\resources;

use extas\components\plugins\Plugin;
use extas\interfaces\IItem;
use extas\interfaces\stages\IStageItemEqual;

class PluginEqual extends Plugin implements IStageItemEqual
{
    public function __invoke(IItem $source, IItem $compareTo, bool &$equal): void
    {
        foreach ($source as $field => $value) {
            if (!isset($compareTo[$field]) || $compareTo[$field] != $value) {
                $equal = false;
                return;
            }
        }
    }
}
