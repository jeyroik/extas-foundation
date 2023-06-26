<?php
namespace extas\interfaces\samples\parameters;

use extas\interfaces\IHasDescription;
use extas\interfaces\IHasName;
use extas\interfaces\IHasValue;
use extas\interfaces\IItem;

/**
 * Interface ISampleParameter
 * 
 * @deprecated Please, use extas\interfaces\parameters\IParam instead
 *
 * @package extas\interfaces\samples\parameters
 * @author jeyroik <jeyroik@gmail.com>
 */
interface ISampleParameter extends IItem, IHasName, IHasValue, IHasDescription
{
    public const SUBJECT = 'extas.sample.parameter';
}
