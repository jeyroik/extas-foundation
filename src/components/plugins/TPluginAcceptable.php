<?php
namespace extas\components\plugins;

use extas\components\SystemContainer;
use extas\interfaces\plugins\IPlugin;
use extas\interfaces\plugins\IPluginRepository;

/**
 * Trait TPluginAcceptable
 *
 * @package extas\components\plugins
 * @author jeyroik@gmail.com
 */
trait TPluginAcceptable
{
    /**
     * @param $stage
     *
     * @return \Generator|IPlugin
     */
    public function getPluginsByStage($stage)
    {
        /**
         * @var $pluginRepo IPluginRepository
         */
        $pluginRepo = SystemContainer::getItem(IPluginRepository::class);
        if ($pluginRepo->hasStagePlugins($stage)) {
            $logIndex = PluginLog::log($this, $stage);
            foreach ($pluginRepo->getStagePlugins($stage) as $plugin) {
                PluginLog::logPluginClass(get_class($plugin), $logIndex);
                yield $plugin;
            }
        }
    }
}
