<?php
namespace tests\resources;

use extas\components\extensions\Extension;

class ExtensionCheckMethod extends Extension
{
    public function getSomething($self): string
    {
        return $self->name;
    }
}
