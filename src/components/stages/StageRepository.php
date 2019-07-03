<?php
namespace extas\components\stages;

use extas\components\repositories\Repository;
use extas\interfaces\stages\IStage;
use extas\interfaces\stages\IStageRepository;

/**
 * Class StageRepository
 *
 * @package extas\components\stages
 * @author jeyroik@gmail.com
 */
class StageRepository extends Repository implements IStageRepository
{
    protected $itemClass = Stage::class;
    protected $pk = IStage::FIELD__NAME;
    protected $scope = 'extas';
    protected $name = 'stages';
    protected $idAs = '';
}
