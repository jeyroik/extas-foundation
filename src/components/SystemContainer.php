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
    protected const DEFAULT__DIST_PATH = '/resources/container.dist.json';

    /**
     * @var ISystemContainer
     */
    protected static ?ISystemContainer $instance = null;

    /**
     * @var Container
     */
    protected ?Container $container = null;

    /**
     * @param string $name
     *
     * @return mixed
     */
    public static function getItem(string $name)
    {
        return static::getInstance()->get($name);
    }

    /**
     * @param string $name
     *
     * @return bool
     */
    public static function hasItem(string $name): bool
    {
        return static::getInstance()->has($name);
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

    public static function refresh(): void
    {
        $path = self::getConfigPath();
        file_put_contents($path, json_encode([]));
    }

    public static function saveItem($alias, $class): void
    {
        $path = self::getConfigPath();
        
        if (!is_file($path)) {
            file_put_contents($path, '[]');
        }

        $config = json_decode(file_get_contents($path), true);
        $config[$alias] = $class;

        file_put_contents($path, json_encode($config));
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
     * @return ISystemContainer
     * @throws
     */
    protected static function getInstance()
    {
        return self::$instance ?: self::$instance = new static();
    }

    public static function getConfigPath(): string
    {
        return getenv('EXTAS__CONTAINER_PATH_STORAGE_LOCK') ?: getcwd() . static::DEFAULT__DIST_PATH;
    }

    public static function getConfig(): array
    {
        $path = self::getConfigPath();

        if (!is_file($path)) {
            return [];
        }

        return json_decode(file_get_contents($path), true);
    }

    /**
     * SystemContainer constructor.
     *
     * @throws \Exception
     */
    protected function __construct()
    {
        $containerConfigPath = self::getConfigPath();

        if (is_file($containerConfigPath)) {
            $this->container = new Container();
            $containerConfig = self::getConfig();

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
     * @param string $name
     *
     * @return mixed
     */
    public function get(string $name)
    {
        return $this->container->get($name);
    }

    /**
     * @param string $name
     *
     * @return bool
     */
    public function has(string $name): bool
    {
        return $this->container->has($name);
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
