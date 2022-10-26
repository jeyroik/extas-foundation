<?php
namespace extas\interfaces\plugins;

use extas\interfaces\IHasClass;
use extas\interfaces\IHasHash;
use extas\interfaces\IHasId;
use extas\interfaces\IHaveConfig;
use extas\interfaces\samples\parameters\IHasSampleParameters;

/**
 * Interface IPlugin
 *
 * @package extas\interfaces\plugins
 * @author jeyroik@gmail.com
 */
interface IPlugin extends \ArrayAccess, \Iterator, IHasClass, IHasId, IHasSampleParameters, IHasHash, IHaveConfig
{
    public const SUBJECT = 'plugin';

    public const FIELD__STAGE = 'stage';
    public const FIELD__PRIORITY = 'priority';

    /**
     * @return int
     */
    public function getPriority(): int;

    /**
     * @param int $priority
     * @return IPlugin
     */
    public function setPriority(int $priority): IPlugin;

    /**
     * @return string
     */
    public function getStage(): string;

    /**
     * @param string $stage
     *
     * @return $this
     */
    public function setStage(string $stage): IPlugin;
}
