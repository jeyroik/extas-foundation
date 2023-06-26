<?php
namespace extas\interfaces\parameters;

use extas\interfaces\IHasDescription;
use extas\interfaces\IHasName;
use extas\interfaces\IHasValue;
use extas\interfaces\IItem;

interface IParam extends IItem, IHasName, IHasDescription, IHasValue
{
    public const SUBJECT = 'extas.param';
}
