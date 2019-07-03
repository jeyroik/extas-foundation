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
    protected $itemClass = Extension::class;
    protected $name = 'extensions';
    protected $scope = 'extas';
    protected $pk = Extension::FIELD__CLASS;
    protected $idAs = '';
}
