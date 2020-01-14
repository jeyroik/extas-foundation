<?php
namespace extas\interfaces;

use extas\interfaces\plugins\IPlugin;

/**
 * Interface IPlugins
 *
 * @package extas\interfaces
 * @author jeyroik@gmail.com
 */
interface IPlugins
{
    /**
     * @param string $stage
     * @param object $riser
     *
     * @return \Generator|IPlugin
     */
    public static function byStage(string $stage, $riser = null);
}
