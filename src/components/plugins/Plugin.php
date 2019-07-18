<?php
namespace extas\components\plugins;

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
    use THasId;
    use THasClass;

    protected $isAllowToStringStage = false;
    protected $isAllowToIntStage = false;
    protected $isAllowToArrayStage = false;
    protected $isAllowCreatedStage = false;
    protected $isAllowAfterStage = false;
    protected $isAllowInitStage = false;

    /**
     * @param string $stage
     *
     * @return $this
     */
    public function setStage(string $stage)
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
