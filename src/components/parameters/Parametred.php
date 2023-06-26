<?php
namespace extas\components\parameters;

use extas\components\Item;
use extas\components\THasDescription;
use extas\components\THasName;
use extas\interfaces\parameters\IParametred;

class Parametred extends Item implements IParametred
{
    use THasName;
    use THasDescription;
    use THasParams;

    protected function getSubjectForExtension(): string
    {
        return static::SUBJECT;
    }
}
