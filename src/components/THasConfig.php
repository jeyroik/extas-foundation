<?php
namespace extas\components;

trait THasConfig
{
    /**
     * @var array
     */
    protected array $config = [];

    /**
     * @param array $config
     */
    public function __construct(array $config = [])
    {
        $this->setConfig($config);
    }

    /**
     * @param $name
     * @return bool
     */
    public function __isset($name)
    {
        return isset($this->config[$name]);
    }
    
    /**
     * @param $config
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
     * @param $name
     * @return mixed|null
     */
    public function __get($name)
    {
        return $this->config[$name] ?? null;
    }

    /**
     * @param $name
     * @param $value
     * @return void
     */
    public function __set($name, $value)
    {
        $this->config[$name] = $value;
    }

    /**
     * @return array
     */
    public function __toArray(): array
    {
        return $this->config;
    }
}
