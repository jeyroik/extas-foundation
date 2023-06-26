<?php
namespace extas\components\parameters;

use extas\components\collections\TBuildAll;
use extas\components\collections\TCollection;
use extas\components\Item;
use extas\interfaces\parameters\IParam;
use extas\interfaces\parameters\IParams;

class Params extends Item implements IParams
{
    use TCollection;
    use TBuildAll;

    public function buildOne(string $name, bool $errorIfMissed = false): IParam
    {
        $this->hasOne($name, $errorIfMissed);

        return new Param($this->getOne($name));
    }

    protected function getSubjectForExtension(): string
    {
        return static::SUBJECT;
    }
}
