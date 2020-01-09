<?php
namespace extas\components\plugins;

use extas\components\Plugins;
use extas\interfaces\plugins\IPlugin;

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
        foreach (Plugins::byStage($stage, $this) as $plugin) {
            yield $plugin;
        }
    }
}
