<?php
namespace extas\interfaces\parameters;

interface IHaveParams
{
    public const FIELD__PARAMS = 'params';

    public function getParams(): array;
    public function buildParams(): IParams;
}
