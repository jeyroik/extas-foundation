<?php
namespace extas\components;

use extas\interfaces\ISystemContainer;
use League\Container\Container;

/**
 * Class SystemContainer
 *
 * @package extas\components
 * @author jeyroik@gmail.com
 */
class SystemContainer implements ISystemContainer
{
    /**
     * @var static
     */
    protected static $instance = null;

    /**
     * @var Container
     */
    protected $container = null;

    /**
     * @param string $name
     *
     * @return mixed
     */
    public static function getItem($name)
    {
        return static::getInstance()->get($name);
    }

    /**
     * @param string $name
     * @param $value
     *
     * @return mixed
     */
    public static function addItem($name, $value)
    {
        return static::getInstance()->add($name, $value);
    }

    /**
     * @return ISystemContainer
     */
    public static function reset()
    {
        self::$instance = null;

        return self::getInstance();
    }

    /**
     * @return static
     * @throws
     */
    protected static function getInstance()
    {
        return self::$instance ?: self::$instance = new static();
    }

    /**
     * SystemContainer constructor.
     *
     * @throws \Exception
     */
    protected function __construct()
    {
        $containerConfigPath = getenv('EXTAS__CONTAINER_PATH_STORAGE_LOCK')
            ?: getenv('EXTAS__BASE_PATH') . '/resources/configs/container.php';

        if (is_file($containerConfigPath)) {
            $this->container = new Container();
            $containerConfig = include $containerConfigPath;

            foreach ($containerConfig as $itemName => $itemValue) {
                $this->container->add($itemName, $itemValue);
            }
        } else {
            throw new \Exception(
                'Missed or restricted container config path "' . $containerConfigPath . '".'
            );
        }
    }

    /**
     * @param $name
     *
     * @return mixed
     */
    public function get($name)
    {
        return $this->container->get($name);
    }

    /**
     * @param $name
     * @param $value
     *
     * @return mixed
     */
    public function add($name, $value)
    {
        return $this->container->add($name, $value);
    }
}
