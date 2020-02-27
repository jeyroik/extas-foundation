<?php
namespace extas\components\repositories\clients\databases;

use extas\interfaces\repositories\drivers\IDriver;
use extas\interfaces\repositories\drivers\IDriverRepository;
use extas\components\SystemContainer;

/**
 * Class DbCurrent
 *
 * @package extas\components\repositories\clients\databases
 * @author jeyroik@gmail.com
 */
class DbCurrent
{
    protected static array $tables = [];

    /**
     * @param $repoName
     * @param $scope
     *
     * @return mixed
     * @throws \Exception
     */
    public static function getTable($repoName, $scope)
    {
        if (!isset(static::$tables[$scope . $repoName])) {

            $repoNameEnv = static::normalize($repoName, true);
            $scopeEnv = static::normalize($scope, true);

            list($tableName, $dbName, $clientDSN, $driverName) = static::getTableDbClientDriver(
                $scope,
                $scopeEnv,
                $repoName,
                $repoNameEnv
            );

            /**
             * @var $driverRepo IDriverRepository
             */
            $driverRepo = SystemContainer::getItem(IDriverRepository::class);
            $driver = $driverRepo->findOne([IDriver::FIELD__NAME => $driverName]);

            if (!$driver) {
                throw new \Exception('Unknown driver "' . $driverName . '"');
            }

            $client = $driver->createClient($clientDSN);
            $db = $client->getDb($dbName);

            static::$tables[$scope . $repoName] = $db->getTable($tableName);
        }

        return static::$tables[$scope . $repoName];
    }

    /**
     * @param $scope
     * @param $scopeEnv
     * @param $repoName
     * @param $repoNameEnv
     *
     * @return array [tableName, dbName, clientDSN, driverName]
     */
    protected static function getTableDbClientDriver($scope, $scopeEnv, $repoName, $repoNameEnv)
    {
        /**
         * env example: EXTAS_TABLE__SOMETHING
         */
        $tableName = getenv($scopeEnv . '_TABLE__' . $repoNameEnv) ?: $scope . '__' . $repoName;

        /**
         * env example: EXTAS_DB__SOMETHING
         */
        $dbName = getenv($scopeEnv . '_DB__' . $repoNameEnv)
            ?: (getenv($scopeEnv . '__DB')
                ?: 'extas');

        $clientDSN = getenv($scopeEnv . '_DSN__' . $repoNameEnv)
            ?: (getenv($scopeEnv . '__DSN')
                ?: getenv('EXTAS__DSN'));

        $driverName = getenv($scopeEnv . '_DRIVER__' . $repoNameEnv)
            ?: (getenv($scopeEnv . '__DRIVER')
                ?: 'mongo');

        return [$tableName, $dbName, $clientDSN, $driverName];
    }

    /**
     * @param $string string
     * @param $isEnv bool
     * @param $delimiter string
     *
     * @return mixed|string
     */
    protected static function normalize($string, $isEnv, $delimiter = '_')
    {
        $normalized = str_replace('.', $delimiter, $string);

        return $isEnv ? strtoupper($normalized) : $normalized;
    }
}
