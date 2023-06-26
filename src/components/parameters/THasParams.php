<?php
namespace extas\components\parameters;

use extas\interfaces\parameters\IHaveParams;
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

    public function buildParams(): IParams
    {
        return new Params($this->getParams());
    }
}
