<?php
namespace extas\components;

use extas\components\plugins\PluginLog;
use extas\components\plugins\PluginRepository;
use extas\interfaces\IPlugins;
use extas\interfaces\plugins\IPlugin;

/**
 * Class Plugins
 *
 * @package extas\components
 * @author jeyroik@gmail.com
 */
class Plugins implements IPlugins
{
    /**
     * @param string $stage
     * @param object $riser
     * @param array $config
     *
     * @return IPlugin|\Generator
     */
    public static function byStage(string $stage, $riser = null, array $config = [])
    {
        $pluginRepo = new PluginRepository();
        $riser = $riser ?: new static();
        $logIndex = PluginLog::log($riser, $stage);

        foreach ($pluginRepo->getStagePlugins($stage, $config) as $plugin) {
            PluginLog::logPluginClass(get_class($plugin), $logIndex);
            yield $plugin;
        }
    }
}
