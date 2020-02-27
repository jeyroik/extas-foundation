<?php
namespace extas\components\plugins;

use extas\components\SystemContainer;
use extas\interfaces\plugins\IPlugin;
use extas\interfaces\plugins\IPluginRepository;
use extas\components\repositories\RepositoryClassObjects;
use extas\interfaces\stages\IStage;
use extas\interfaces\stages\IStageRepository;

/**
 * Class PluginRepository
 *
 * @package extas\components\plugins
 * @author jeyroik@gmail.com
 */
class PluginRepository extends RepositoryClassObjects implements IPluginRepository
{
    /**
     * @var IPlugin[]
     */
    protected static array $stagesWithPlugins = [];

    /**
     * @var IStageRepository
     */
    protected static ?IStageRepository $stageRepo = null;

    protected string $itemClass = Plugin::class;
    protected string $name = 'plugins';
    protected string $pk = Plugin::FIELD__CLASS;
    protected string $scope = 'extas';
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

    /**
     * @param $stage
     *
     * @return \Generator
     * @throws
     */
    public function getStagePlugins($stage)
    {
        if (!isset(self::$stagesWithPlugins[$stage])) {
            $this->initStageRepo();

            $stageObj = self::$stageRepo->one([IStage::FIELD__NAME => $stage, IStage::FIELD__HAS_PLUGINS => true]);
            self::$stagesWithPlugins[$stage] = $stageObj ? $this->all([IPlugin::FIELD__STAGE => $stage]) : [];
        }

        $plugins = self::$stagesWithPlugins[$stage];

        foreach ($plugins as $plugin) {
            yield $plugin;
        }
    }

    /**
     * @return $this
     */
    protected function initStageRepo()
    {
        if (!self::$stageRepo) {
            self::$stageRepo = SystemContainer::getItem(IStageRepository::class);
        }

        return $this;
    }
}
