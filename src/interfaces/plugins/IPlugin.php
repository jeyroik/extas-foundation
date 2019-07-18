<?php
namespace extas\interfaces\plugins;

use extas\interfaces\IHasClass;
use extas\interfaces\IHasId;
use extas\interfaces\IItem;

/**
 * Interface IPlugin
 *
 * @package extas\interfaces\plugins
 * @author jeyroik@gmail.com
 */
interface IPlugin extends IItem, IHasId, IHasClass
{
    const SUBJECT = 'plugin';

    const STAGE__PLUGIN_INIT = 'plugin.init';
    const STAGE__PLUGIN_AFTER = 'plugin.after';

    const FIELD__STAGE = 'stage';

    /**
     * @return string
     */
    public function getStage(): string;

    /**
     * @param string $stage
     *
     * @return $this
     */
    public function setStage(string $stage);
}
