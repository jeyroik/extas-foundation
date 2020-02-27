<?php
namespace extas\components\plugins;

use extas\interfaces\plugins\IPlugin;

/**
 * Class PluginLog
 *
 * @package extas\components\plugins
 * @author jeyroik@gmail.com
 */
class PluginLog
{
    /**
     * bc - stage count
     * bct - stage count total
     * pc - plugin count
     * pct - plugin count total
     * rc - riser count
     * rct - riser count total
     *
     * @var array
     */
    protected static array $pluginLog = [
        'count' => [
            'bs' => [],
            'bst' => 0,
            'pc' => [],
            'pct' => 0,
            'rc' => [],
            'rct' => 0
        ],
        'log' => []
    ];

    /**
     * @return array
     */
    public static function getLog()
    {
        return static::$pluginLog;
    }

    /**
     * @return int
     */
    public static function getLogIndex()
    {
        return count(self::$pluginLog['log']);
    }

    /**
     * @param $riser
     * @param $stage
     *
     * @return int
     */
    public static function log($riser, $stage)
    {
        $riserClass = self::getRiser($riser);
        $logIndex = static::getLogIndex();
        self::$pluginLog['log'][$logIndex] = [
            'stage' => $stage,
            'riser' => $riserClass,
            'plugins_count' => 0,
            'plugins' => []
        ];
        static::logPluginRiser($riserClass);
        static::logPluginStage($stage);

        return $logIndex;
    }

    /**
     * @param $stage
     */
    public static function logPluginStage($stage)
    {
        self::$pluginLog['count']['bs'][$stage] = self::$pluginLog['count']['bs'][$stage] ?? 0;
        self::$pluginLog['count']['bs'][$stage] ++;
        self::$pluginLog['count']['bst'] ++;
    }

    /**
     * @param $riserClass
     */
    public static function logPluginRiser($riserClass)
    {
        self::$pluginLog['count']['rc'][$riserClass] = self::$pluginLog['count']['rc'][$riserClass] ?? 0;
        self::$pluginLog['count']['rc'][$riserClass] ++;
        self::$pluginLog['count']['rct'] ++;
    }

    /**
     * @param $class
     * @param $logIndex
     */
    public static function logPluginClass($class, $logIndex)
    {
        self::$pluginLog['log'][$logIndex]['plugins'][] = $class;
        self::$pluginLog['log'][$logIndex]['plugins_count'] ++;

        self::$pluginLog['count']['pct'] ++;
        self::$pluginLog['count']['pc'][$class] = self::$pluginLog['count']['pc'][$class] ?? 0;
        self::$pluginLog['count']['pc'][$class] ++;
    }

    /**
     * @param $riser
     *
     * @return string
     */
    protected static function getRiser($riser)
    {
        $riserClass = get_class($riser);

        if ($riserClass == Plugin::class) {
            /**
             * @var $riser IPlugin
             */
            $riserClass = $riser->getClass();
        }

        return $riserClass;
    }
}
