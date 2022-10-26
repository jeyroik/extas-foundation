<?php
namespace extas\components;

use extas\interfaces\IPlugins;
use extas\interfaces\plugins\IPlugin;
use extas\interfaces\repositories\IRepository;

/**
 * Class Plugins
 *
 * @package extas\components
 * @author jeyroik@gmail.com
 */
class Plugins implements IPlugins
{
    protected static array $stagesWithPlugins = [];

    /**
     * @param string $stage
     * @param object $riser
     * @param array $config
     *
     * @return IPlugin|\Generator
     */
    public static function byStage(string $stage, $riser = null, array $config = [])
    {
        $pluginRepo = SystemContainer::getItem(getenv('EXTAS__PLUGINS_REPOSITORY') ?: 'plugins');
        $riser = $riser ?: new static();

        foreach (self::getStagePlugins($pluginRepo, $stage, $config) as $plugin) {
            yield $plugin;
        }
    }

    protected static function getStagePlugins(IRepository $pluginRepo, string $stage, array $config = [])
    {
        $hash = $stage . sha1(json_encode($config));
        if (!isset(self::$stagesWithPlugins[$hash])) {
            /**
             * @var $plugins IPlugin[]
             */
            $r = $pluginRepo->all(
                [IPlugin::FIELD__STAGE => $stage],
                0,
                0,
                [IPlugin::FIELD__PRIORITY, -1]
            );
            self::$stagesWithPlugins[$hash] = $r;
        }

        foreach (self::$stagesWithPlugins[$hash] as $plugin) {
            $config[IPlugin::FIELD__STAGE] = $stage;
            $config[IPlugin::FIELD__PARAMETERS] = $plugin->getParametersOptions();
            yield $plugin->buildClassWithParameters($config);
        }
    }

    public static function reset(): void
    {
        self::$stagesWithPlugins = [];
    }
}
