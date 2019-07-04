<?php
namespace extas\components\plugins;

use extas\interfaces\plugins\IPlugin;
use extas\interfaces\plugins\IPluginRepository;
use extas\components\repositories\RepositoryClassObjects;

/**
 * Class PluginRepository
 *
 * @package extas\components\plugins
 * @author jeyroik@gmail.com
 */
class PluginRepository extends RepositoryClassObjects implements IPluginRepository
{
    protected static $stagesWithPlugins = [];

    protected $itemClass = Plugin::class;
    protected $name = 'plugins';
    protected $pk = Plugin::FIELD__CLASS;
    protected $scope = 'extas';
    protected $idAs = '';

    protected $isAllowInitStage = false;
    protected $isAllowAfterStage = false;
    protected $isAllowCreatedStage = false;
    protected $isAllowToArrayStage = false;
    protected $isAllowToIntStage = false;
    protected $isAllowToStringStage = false;

    /**
     * @param $stage
     *
     * @return bool
     * @throws
     */
    public function hasStagePlugins($stage): bool
    {
        if (empty(self::$stagesWithPlugins)) {
            $this->loadStagesWithPlugins();
        }

        return isset(self::$stagesWithPlugins[$stage]);
    }

    /**
     * @param $stage
     *
     * @return \Generator
     * @throws
     */
    public function getStagePlugins($stage)
    {
        if (empty(self::$stagesWithPlugins)) {
            $this->loadStagesWithPlugins();
        }

        $plugins = self::$stagesWithPlugins[$stage] ?? [];

        foreach ($plugins as $index => $plugin) {
            if (is_string($plugin)) {
                $plugin = new $plugin();
                self::$stagesWithPlugins[$stage][$index] = $plugin;
            }
            yield $plugin;
        }
    }

    /**
     * @throws \Exception
     */
    protected function loadStagesWithPlugins()
    {
        self::$stagesWithPlugins = $this->group(IPlugin::FIELD__STAGE, IPlugin::FIELD__CLASS);
    }
}
