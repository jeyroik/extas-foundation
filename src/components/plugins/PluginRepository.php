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
    protected static $stagesWithPlugins = [];

    /**
     * @var IStageRepository
     */
    protected static $stageRepo = null;

    protected $itemClass = Plugin::class;
    protected $name = 'plugins';
    protected $pk = Plugin::FIELD__CLASS;
    protected $scope = 'extas';
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
