<?php
namespace extas\components\parameters;

use extas\components\Item;
use extas\components\THasDescription;
use extas\components\THasName;
use extas\components\THasValue;
use extas\interfaces\parameters\IParametred;

class Parametred extends Item implements IParametred
{
    use THasName;
    use THasDescription;
    use THasParams;
    use THasValue;

    protected function getSubjectForExtension(): string
    {
        return static::SUBJECT;
    }
}
