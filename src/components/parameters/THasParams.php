<?php
namespace extas\components\parameters;

use extas\interfaces\parameters\IHaveParams;
use extas\interfaces\parameters\IParam;
use extas\interfaces\parameters\IParams;

/**
 * @property array $config
 */
trait THasParams
{
    public function getParams(): array
    {
        return $this->config[IHaveParams::FIELD__PARAMS] ?? [];
    }

    public function setParams(array $params): static
    {
        $this->config[IHaveParams::FIELD__PARAMS] = $params;

        return $this;
    }

    public function addParam(IParam $param): static
    {
        $this->config[IHaveParams::FIELD__PARAMS][$param->getName()] = $param->__toArray();

        return $this;
    }

    public function buildParams(): IParams
    {
        return new Params($this->getParams());
    }

    public function getParamsValues(): array
    {
        return array_column($this->getParams(), IParam::FIELD__VALUE, IParam::FIELD__NAME);
    }
}
