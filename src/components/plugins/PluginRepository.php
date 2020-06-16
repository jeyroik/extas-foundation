<?php
namespace extas\components\plugins;

use extas\components\repositories\Repository;
use extas\interfaces\plugins\IPlugin;
use extas\interfaces\plugins\IPluginRepository;

/**
 * Class PluginRepository
 *
 * @package extas\components\plugins
 * @author jeyroik@gmail.com
 */
class PluginRepository extends Repository implements IPluginRepository
{
    /**
     * @var IPlugin[]
     */
    protected static array $stagesWithPlugins = [];

    protected string $itemClass = Plugin::class;
    protected string $name = 'plugins';
    protected string $pk = Plugin::FIELD__CLASS;
    protected string $scope = 'extas';

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
     * @param string $stage
     * @param array $config
     *
     * @return \Generator
     * @throws
     */
    public function getStagePlugins(string $stage, array $config = [])
    {
        if (!isset(self::$stagesWithPlugins[$stage])) {
            /**
             * @var $plugins IPlugin[]
             */
            self::$stagesWithPlugins[$stage] = [];
            $plugins = $this->all(
                [IPlugin::FIELD__STAGE => $stage],
                0,
                0,
                [IPlugin::FIELD__PRIORITY, -1]
            );
            foreach ($plugins as $plugin) {
                $config[IPlugin::FIELD__STAGE] = $stage;
                $config[IPlugin::FIELD__PARAMETERS] = $plugin->getParametersOptions();
                self::$stagesWithPlugins[$stage][] = $plugin->buildClassWithParameters($config);
            }
        }

        $plugins = self::$stagesWithPlugins[$stage];

        foreach ($plugins as $plugin) {
            yield $plugin;
        }
    }
}
