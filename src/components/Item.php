<?php
namespace extas\components;

use extas\components\extensions\TExtendable;
use extas\interfaces\IItem;
use extas\interfaces\repositories\IRepository;

/**
 * Class Item
 *
 * @property $id
 * @property int $created_at
 * @property int $updated_at
 *
 * @package extas\components
 * @author jeyroik@gmail.com
 */
abstract class Item implements IItem
{
    use TExtendable;
    use TIsArray;

    /**
     * @var array
     */
    protected array $config = [];

    protected bool $isAllowInitStage = true;
    protected bool $isAllowAfterStage = true;
    protected bool $isAllowCreatedStage = true;
    protected bool $isAllowToArrayStage = true;
    protected bool $isAllowToStringStage = true;
    protected bool $isAllowToIntStage = true;

    /**
     * Item constructor.
     *
     * @param array $config
     */
    public function __construct(array $config = [])
    {
        $this->setConfig($config);
        $this->isAllowInitStage && $this->triggerInit();
    }

    /**
     * @return void
     */
    public function __destruct()
    {
        if ($this->isAllowAfterStage) {
            foreach ($this->getPluginsByStage($this->getBaseStageName('after')) as $plugin) {
                $plugin($this);
            }
        }
    }

    /**
     * @param $name
     *
     * @return mixed|null
     */
    public function __get($name)
    {
        return $this->config[$name] ?? null;
    }

    /**
     * @param $name
     * @param $value
     *
     * @return void
     */
    public function __set($name, $value)
    {
        $this->config[$name] = $value;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        $string = '';
        $this->isAllowToStringStage && $this->triggerStageTo('string', $string);

        return $string;
    }

    /**
     * @return array
     */
    public function __toArray(): array
    {
        $array = $this->config;
        $this->isAllowToArrayStage && $this->triggerStageTo('array', $array);

        return $array;
    }

    /**
     * @return int
     */
    public function __toInt(): int
    {
        $int = 0;
        $this->isAllowToIntStage && $this->triggerStageTo('int', $int);

        return $int;
    }

    /**
     * @param $item
     * @param $repo IRepository
     *
     * @return $this|IItem
     */
    public function __created($item, $repo)
    {
        if ($this->isAllowCreatedStage) {
            foreach ($this->getPluginsByStage($this->getBaseStageName('created')) as $plugin) {
                $plugin($this, $item, $repo);
            }
        }

        return $this;
    }

    /**
     * @param $config
     *
     * @return IItem|mixed
     */
    protected function setConfig($config)
    {
        !empty($config) && $this->config = $config;
        $this->keyMap = array_keys($config);
        $this->currentKey = 0;

        return $this;
    }

    /**
     * @return $this
     */
    protected function triggerInit()
    {
        foreach ($this->getPluginsByStage($this->getBaseStageName('init')) as $plugin) {
            $plugin($this);
        }

        return $this;
    }

    /**
     * @param string $stage
     * @param mixed $result
     * @return mixed
     */
    protected function triggerStageTo(string $stage, &$result)
    {
        foreach ($this->getPluginsByStage($this->getBaseStageName('to.' . $stage)) as $plugin) {
            $plugin($this, $result);
        }

        return $result;
    }

    /**
     * @param $stage
     *
     * @return string
     */
    protected function getBaseStageName($stage)
    {
        return $this->getSubjectForExtension() . '.' . $stage;
    }
}
