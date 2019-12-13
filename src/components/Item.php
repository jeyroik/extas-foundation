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

    /**
     * @var array
     */
    protected $config = [];

    /**
     * @var int
     */
    protected $currentKey = 0;

    /**
     * @var array
     */
    protected $keyMap = [];

    protected $isAllowInitStage = true;
    protected $isAllowAfterStage = true;
    protected $isAllowCreatedStage = true;
    protected $isAllowToArrayStage = true;
    protected $isAllowToStringStage = true;
    protected $isAllowToIntStage = true;

    /**
     * Item constructor.
     *
     * @param array $config
     */
    public function __construct($config = [])
    {
        $this->setConfig($config??[]);
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
     * @return array
     */
    public function __toArray(): array
    {
        $array = $this->config;

        if ($this->isAllowToArrayStage) {
            foreach ($this->getPluginsByStage($this->getBaseStageName('.to.array')) as $plugin) {
                $plugin($this, $array);
            }
        }

        return $array;
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
        $string = (string) $this->id;

        if ($this->isAllowToStringStage) {
            foreach ($this->getPluginsByStage($this->getBaseStageName('.to.string')) as $plugin) {
                $plugin($this, $string);
            }
        }

        return $string;
    }

    /**
     * @return int
     */
    public function __toInt(): int
    {
        $int = (int) $this->id;

        if ($this->isAllowToIntStage) {
            foreach ($this->getPluginsByStage($this->getBaseStageName('.to.int')) as $plugin) {
                $plugin($this, $int);
            }
        }

        return $int;
    }

    /**
     * @param mixed $offset
     *
     * @return bool
     */
    public function offsetExists($offset)
    {
        return isset($this->config[$offset]);
    }

    /**
     * @param mixed $offset
     *
     * @return mixed|null
     */
    public function offsetGet($offset)
    {
        return $this->config[$offset] ?? null;
    }

    /**
     * @param mixed $offset
     * @param mixed $value
     */
    public function offsetSet($offset, $value)
    {
        $this->config[$offset] = $value;
    }

    /**
     * @param mixed $offset
     */
    public function offsetUnset($offset)
    {
        unset($this->config[$offset]);
    }

    /**
     * @return bool
     */
    public function valid()
    {
        return isset($this->keyMap[$this->currentKey]);
    }

    /**
     * @return string|null
     */
    public function key()
    {
        return $this->keyMap[$this->currentKey] ?? null;
    }

    /**
     * @return void
     */
    public function next()
    {
        $this->currentKey++;
    }

    /**
     * @return mixed
     */
    public function current()
    {
        return $this->config[$this->keyMap[$this->currentKey]];
    }

    /**
     * @return void
     */
    public function rewind()
    {
        $this->currentKey = 0;
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
     * @param $stage
     *
     * @return string
     */
    protected function getBaseStageName($stage)
    {
        return $this->getSubjectForExtension() . '.' . $stage;
    }
}
