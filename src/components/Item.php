<?php
namespace extas\components;

use extas\components\THasConfig;
use extas\components\extensions\TExtendable;
use extas\interfaces\IItem;
use extas\interfaces\stages\IStageItemEqual;
use extas\interfaces\stages\IStageItemInit;

/**
 * Class Item
 *
 * @package extas\components
 * @author jeyroik@gmail.com
 */
abstract class Item implements IItem
{
    use TExtendable;
    use TAsArray;
    use THasConfig;

    /**
     * Item constructor.
     *
     * @param array $config
     */
    public function __construct(array $config = [])
    {
        $this->setConfig($config);
        $this->triggerInit();
    }

    /**
     * @return void
     */
    public function __destruct()
    {
        foreach ($this->getPluginsByStage($this->getBaseStageName('after')) as $plugin) {
            $plugin($this);
        }
    }

    /**
     * @param $name
     * @return bool
     */
    public function __isset(string $name): bool
    {
        return isset($this->config[$name]);
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        $string = '';
        $this->triggerStageTo('string', $string);

        return $string;
    }

    /**
     * @return array
     */
    public function __toArray(): array
    {
        $array = $this->config;
        $this->triggerStageTo('array', $array);

        return $array;
    }

    /**
     * @return int
     */
    public function __toInt(): int
    {
        $int = 0;
        $this->triggerStageTo('int', $int);

        return $int;
    }

    /**
     * todo: remove $item argument
     *
     * @param $item
     * @param $repo IRepository
     * @return $this|IItem
     */
    public function __created($item, $repo)
    {
        foreach ($this->getPluginsByStage($this->getBaseStageName('created')) as $plugin) {
            $plugin($this, $item, $repo);
        }

        return $this;
    }

    /**
     * @deprecated please, use jsonSerialize() - it would automatically used on json_encode($item), will be removed in 7.0.0
     * @return string
     */
    public function __toJson(): string
    {
        $dataToJson = $this->__toArray();
        $this->triggerStageTo('json', $dataToJson);

        return json_encode($dataToJson);
    }

    /**
     * @return array
     */
    public function jsonSerialize(): mixed
    {
        return $this->__toArray();
    }

    /**
     * @param IItem $other
     * @return bool
     */
    public function __equal(IItem $other): bool
    {
        $equal = true;

        foreach ($this->getPluginsByStage($this->getBaseStageName(IStageItemEqual::NAME)) as $plugin) {
            $plugin($this, $other, $equal);
        }

        return $equal;
    }

    /**
     * @return string
     */
    public function __subject(): string
    {
        return $this->getSubjectForExtension();
    }

    /**
     * @param array $attributes
     * @return $this|Item
     */
    public function __select(...$attributes)
    {
        $current = $this->__toArray();
        $filtered = [];
        $attributes = array_flip($attributes);

        foreach ($current as $name => $value) {
            if (isset($attributes[$name])) {
                $filtered[$name] = $value;
            }
        }

        return new static($filtered);
    }

    /**
     * @param string ...$params
     * @return bool
     */
    public function has(...$params): bool
    {
        $hasAll = true;

        foreach ($params as $param) {
            if (!isset($this->config[$param])) {
                $hasAll = false;
                break;
            }
        }

        return $hasAll;
    }

    /**
     * @return $this
     */
    protected function triggerInit()
    {
        foreach ($this->getPluginsByStage($this->getBaseStageName(IStageItemInit::NAME__SUFFIX)) as $plugin) {
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
     * @return string
     */
    protected function getBaseStageName($stage)
    {
        return $this->getSubjectForExtension() . '.' . $stage;
    }
}
