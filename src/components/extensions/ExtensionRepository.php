<?php
namespace extas\components\extensions;

use extas\components\repositories\Repository;
use extas\interfaces\extensions\IExtensionRepository;

/**
 * Class ExtensionRepository
 *
 * @package extas\components\extensions
 * @author jeyroik@gmail.com
 */
class ExtensionRepository extends Repository implements IExtensionRepository
{
    protected string $itemClass = Extension::class;
    protected string $name = 'extensions';
    protected string $scope = 'extas';
    protected string $pk = Extension::FIELD__CLASS;
}
