<?php
namespace extas\components\samples\parameters;

use extas\components\Item;
use extas\components\THasDescription;
use extas\components\THasName;
use extas\components\THasValue;
use extas\interfaces\samples\parameters\ISampleParameter;

/**
 * Class SampleParameter
 *
 * @package extas\components\samples\parameters
 * @author jeyroik@gmail.com
 */
class SampleParameter extends Item implements ISampleParameter
{
    use THasName;
    use THasValue;
    use THasDescription;

    /**
     * @return string
     */
    protected function getSubjectForExtension(): string
    {
        return static::SUBJECT;
    }
}
