<?php
namespace extas\interfaces\plugins;

use extas\interfaces\IItem;

/**
 * Interface IPlugin
 *
 * @package extas\interfaces\plugins
 * @author jeyroik@gmail.com
 */
interface IPlugin extends IItem
{
    const SUBJECT = 'plugin';

    const STAGE__PLUGIN_INIT = 'plugin.init';
    const STAGE__PLUGIN_AFTER = 'plugin.after';

    const FIELD__CLASS = 'class';
    const FIELD__STAGE = 'stage';
    const FIELD__ID = 'id';

    /**
     * @return string
     */
    public function getClass(): string;

    /**
     * @return string
     */
    public function getId(): string;

    /**
     * @return string
     */
    public function getStage(): string;

    /**
     * @param $id
     *
     * @return $this
     */
    public function setId($id);

    /**
     * @param $class
     *
     * @return $this
     */
    public function setClass($class);

    /**
     * @param $stage
     *
     * @return $this
     */
    public function setStage($stage);
}
