<?php
namespace extas\components\extensions;

use extas\components\repositories\RepositoryClassObjects;
use extas\interfaces\extensions\IExtensionRepository;

/**
 * Class ExtensionRepository
 *
 * @package extas\components\extensions
 * @author jeyroik@gmail.com
 */
class ExtensionRepository extends RepositoryClassObjects implements IExtensionRepository
{
    protected string $itemClass = Extension::class;
    protected string $name = 'extensions';
    protected string $scope = 'extas';
    protected string $pk = Extension::FIELD__CLASS;
    protected string $idAs = '';
}
