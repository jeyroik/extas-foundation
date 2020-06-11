<?php
namespace extas\components\plugins;

use extas\components\samples\parameters\THasSampleParameters;
use extas\components\THasClass;
use extas\components\THasId;
use extas\interfaces\plugins\IPlugin;
use extas\components\Item;

/**
 * Class Plugin
 *
 * @property string $class
 * @property string $version
 * @property string $stage
 *
 * @package extas\components\plugins
 * @author jeyroik@gmail.com
 */
class Plugin extends Item implements IPlugin
{
    use THasClass;
    use THasId;
    use THasSampleParameters;

    protected bool $isAllowToStringStage = false;
    protected bool $isAllowToIntStage = false;
    protected bool $isAllowToArrayStage = false;
    protected bool $isAllowCreatedStage = false;
    protected bool $isAllowAfterStage = false;
    protected bool $isAllowInitStage = false;

    /**
     * @return int
     */
    public function getPriority(): int
    {
        return $this->config[static::FIELD__PRIORITY] ?? 0;
    }

    /**
     * @param int $priority
     * @return IPlugin
     */
    public function setPriority(int $priority): IPlugin
    {
        $this->config[static::FIELD__PRIORITY] = $priority;

        return $this;
    }

    /**
     * @param string $stage
     *
     * @return $this
     */
    public function setStage(string $stage): IPlugin
    {
        $this->config[static::FIELD__STAGE] = $stage;

        return $this;
    }

    /**
     * @return string
     */
    public function getStage(): string
    {
        return $this->config[static::FIELD__STAGE] ?? '';
    }

    /**
     * @return string
     */
    protected function getSubjectForExtension(): string
    {
        return static::SUBJECT;
    }
}
