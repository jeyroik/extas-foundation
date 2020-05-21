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
     * @param string $stage
     * @param array $config
     *
     * @return \Generator|IPlugin
     */
    public function getPluginsByStage(string $stage, array $config = [])
    {
        foreach (Plugins::byStage($stage, $this, $config) as $plugin) {
            yield $plugin;
        }
    }
}
