<?php
namespace extas\components\repositories\drivers;

use extas\components\exceptions\MissedOrUnknown;
use extas\interfaces\repositories\drivers\IDriver;
use extas\interfaces\repositories\drivers\IDriverRepository;

/**
 * Class DriverRepository
 *
 * @package extas\components\repositories\drivers
 * @author jeyroik@gmail.com
 */
class DriverRepository implements IDriverRepository
{
    public const FIELD__DRIVERS_STORAGE_PATH = 'drivers.storage.path';

    /**
     * [
     *      [
     *          name => name1,
     *          class => class1
     *      ],
     *      ...
     * ]
     *
     * @var array
     */
    protected static array $storage = [];

    /**
     * @var array
     */
    protected array $config = [];

    /**
     * DriverRepository constructor.
     *
     * @param array $config
     *
     * @throws \Exception
     */
    public function __construct($config = [])
    {
        $this->config = $config;

        $this->initStorage();
    }

    /**
     * @param $where
     *
     * @return IDriver|null
     */
    public function findOne($where)
    {
        foreach ($where as $fieldName => $value) {
            $byField = array_column(self::$storage, IDriver::FIELD__CLASS, $fieldName);
            if (isset($byField[$value])) {
                $driverClass = $byField[$value];

                return new $driverClass();
            }
        }

        return null;
    }

    /**
     * @return IDriver[]
     */
    public function findAll()
    {
        $drivers = [];

        foreach (self::$storage as $driverData) {
            $driverClass = $driverData[IDriver::FIELD__CLASS] ?? '';
            $drivers[] = new $driverClass();
        }

        return $drivers;
    }

    /**
     * @return $this
     * @throws MissedOrUnknown
     */
    protected function initStorage()
    {
        if (empty(self::$storage)) {
            $manualPath = $this->config[static::FIELD__DRIVERS_STORAGE_PATH] ?? '';
            $driverStoragePath = $manualPath
                ?: (getenv('EXTAS__DRIVERS_STORAGE_PATH')
                    ?: getcwd() . '/resources/drivers.dist.json');

            if (!is_file($driverStoragePath)) {
                throw new MissedOrUnknown('driver storage path "' . $driverStoragePath . '"');
            }

            self::$storage = json_decode(file_get_contents($driverStoragePath), true);
        }

        return $this;
    }
}
