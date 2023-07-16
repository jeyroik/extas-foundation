<?php
namespace extas\components\parameters;

use extas\components\exceptions\MissedOrUnknown;
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
        $this[IHaveParams::FIELD__PARAMS] = $params;

        return $this;
    }

    public function addParam(IParam $param): static
    {
        $params = $this->getParams();
        $params[$param->getName()] = $param->__toArray();

        return $this->setParams($params);
    }

    public function buildParams(): IParams
    {
        return new Params($this->getParams());
    }

    public function getParamsValues(): array
    {
        return array_column($this->getParams(), IParam::FIELD__VALUE, IParam::FIELD__NAME);
    }

    public function setParamValue(string $paramName, mixed $value): static
    {
        $params = $this->buildParams();
        
        if (!$params->hasOne($paramName)) {
            throw new MissedOrUnknown('parameter "' . $paramName . '"');
        }

        $this->config[IHaveParams::FIELD__PARAMS][$paramName][IParam::FIELD__VALUE] = $value;

        return $this;
    }
}
