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
    protected string $itemClass = Stage::class;
    protected string $pk = IStage::FIELD__NAME;
    protected string $scope = 'extas';
    protected string $name = 'stages';
    protected string $idAs = '';

    /**
     * Item constraints
     */
    protected bool $isAllowInitStage = false;
    protected bool $isAllowAfterStage = false;
    protected bool $isAllowCreatedStage = false;
    protected bool $isAllowToArrayStage = false;
    protected bool $isAllowToIntStage = false;
    protected bool $isAllowToStringStage = false;

    /**
     * Repository constraints
     */
    protected bool $isAllowCreateBeforeStage = false;
    protected bool $isAllowCreateAfterStage = false;
    protected bool $isAllowUpdateBeforeStage = false;
    protected bool $isAllowUpdateAfterStage = false;
    protected bool $isAllowDeleteBeforeStage = false;
    protected bool $isAllowDeleteAfterStage = false;
    protected bool $isAllowFindAfterStage = false;
}
