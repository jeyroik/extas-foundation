<?php
namespace extas\interfaces\parameters;

use extas\interfaces\IHasDescription;
use extas\interfaces\IHasName;
use extas\interfaces\IItem;

interface IParametred extends IItem, IHasName, IHasDescription, IHaveParams
{
    public const SUBJECT = 'extas.parametred';
}
