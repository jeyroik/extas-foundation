<?php
namespace extas\components\parameters;

use extas\components\collections\TBuildAll;
use extas\components\collections\TCollection;
use extas\components\Item;
use extas\interfaces\parameters\IParametred;
use extas\interfaces\parameters\IParametredCollection;

class ParametredCollection extends Item implements IParametredCollection
{
    use TCollection;
    use TBuildAll;

    public function buildOne(string $name, bool $errorIfMissed = false): IParametred
    {
        $this->hasOne($name, $errorIfMissed);

        return new Parametred($this->getOne($name));
    }

    protected function getSubjectForExtension(): string
    {
        return static::SUBJECT;
    }
}
