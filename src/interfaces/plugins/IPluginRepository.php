<?php
namespace extas\interfaces\plugins;

use extas\interfaces\repositories\IRepository;

/**
 * Interface IPluginRepository
 *
 * @package extas\interfaces\plugins
 * @author jeyroik@gmail.com
 */
interface IPluginRepository extends IRepository
{
    /**
     * @param $stage
     *
     * @return \Generator
     */
    public function getStagePlugins($stage);
}
