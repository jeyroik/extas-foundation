<?php
namespace extas\components\parameters;

use extas\components\Item;
use extas\components\THasDescription;
use extas\components\THasName;
use extas\components\THasValue;
use extas\interfaces\parameters\IParam;

class Param extends Item implements IParam
{
    use THasName;
    use THasDescription;
    use THasValue;

    protected function getSubjectForExtension(): string
    {
        return static::SUBJECT;
    }
}
