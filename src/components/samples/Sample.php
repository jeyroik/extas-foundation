<?php
namespace extas\components\samples;

use extas\components\Item;
use extas\components\samples\parameters\THasSampleParameters;
use extas\components\THasDescription;
use extas\components\THasName;
use extas\components\THasUpdatedAt;
use extas\interfaces\samples\ISample;

/**
 * Class Sample
 *
 * @package extas\components\samples
 * @author jeyroik@gmail.com
 */
class Sample extends Item implements ISample
{
    use THasName;
    use THasDescription;
    use THasSampleParameters;
    use THasUpdatedAt;

    /**
     * @return string
     */
    protected function getSubjectForExtension(): string
    {
        return static::SUBJECT;
    }
}
