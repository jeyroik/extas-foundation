<?php
namespace extas\components;

use extas\components\plugins\PluginLog;
use extas\interfaces\IPlugins;
use extas\interfaces\plugins\IPlugin;
use extas\interfaces\plugins\IPluginRepository;

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
     * @param callable $riser
     *
     * @return IPlugin|\Generator
     */
    public static function byStage(string $stage, callable $riser = null)
    {
        /**
         * @var $pluginRepo IPluginRepository
         */
        $pluginRepo = SystemContainer::getItem(IPluginRepository::class);
        $riser = $riser ?: new static();
        $logIndex = PluginLog::log($riser, $stage);

        foreach ($pluginRepo->getStagePlugins($stage) as $plugin) {
            PluginLog::logPluginClass(get_class($plugin), $logIndex);
            yield $plugin;
        }
    }
}
