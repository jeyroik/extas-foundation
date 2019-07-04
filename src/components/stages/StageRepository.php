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

    /**
     * Item constraints
     */
    protected $isAllowInitStage = false;
    protected $isAllowAfterStage = false;
    protected $isAllowCreatedStage = false;
    protected $isAllowToArrayStage = false;
    protected $isAllowToIntStage = false;
    protected $isAllowToStringStage = false;

    /**
     * Repository constraints
     */
    protected $isAllowCreateBeforeStage = false;
    protected $isAllowCreateAfterStage = false;
    protected $isAllowUpdateBeforeStage = false;
    protected $isAllowUpdateAfterStage = false;
    protected $isAllowDeleteBeforeStage = false;
    protected $isAllowDeleteAfterStage = false;
    protected $isAllowFindAfterStage = false;
}
