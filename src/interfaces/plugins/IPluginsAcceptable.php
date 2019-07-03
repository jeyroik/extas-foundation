<?php
namespace extas\interfaces\plugins;

/**
 * Interface IPluginsAcceptable
 *
 * @package extas\interfaces\plugins
 * @author jeyroik@gmail.com
 */
interface IPluginsAcceptable
{
    /**
     * @param string $stage
     *
     * @return IPlugin[]
     */
    public function getPluginsByStage($stage);
}
