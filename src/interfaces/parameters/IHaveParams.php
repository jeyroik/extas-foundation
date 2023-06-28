<?php
namespace extas\interfaces\parameters;

interface IHaveParams
{
    public const FIELD__PARAMS = 'params';

    public function getParams(): array;
    public function setParams(array $params): static;
    public function addParam(IParam $param): static;
    public function buildParams(): IParams;
    public function getParamsValues(): array;
}
