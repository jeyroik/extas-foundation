<?php
namespace extas\components\plugins;

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
    public $preDefinedClass = '';
    public $preDefinedStage = '';

    protected $isAllowToStringStage = false;
    protected $isAllowToIntStage = false;
    protected $isAllowToArrayStage = false;
    protected $isAllowCreatedStage = false;
    protected $isAllowAfterStage = false;
    protected $isAllowInitStage = false;

    /**
     * @param $config
     *
     * @return IPlugin
     */
    protected function setConfig($config)
    {
        $this->preDefinedClass && $config[static::FIELD__CLASS] = $this->preDefinedClass;
        $this->preDefinedStage && $config[static::FIELD__STAGE] = $this->preDefinedStage;

        return parent::setConfig($config);
    }

    /**
     * @param $stage
     *
     * @return $this
     */
    public function setStage($stage)
    {
        $this->stage = $stage;

        return $this;
    }

    /**
     * @param $class
     *
     * @return $this
     */
    public function setClass($class)
    {
        $this->class = $class;

        return $this;
    }

    /**
     * @param $id
     *
     * @return $this
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * @return string
     */
    public function getId(): string
    {
        return (string) $this->id;
    }

    /**
     * @return string
     */
    public function getClass(): string
    {
        return $this->config[static::FIELD__CLASS] ?? '';
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
