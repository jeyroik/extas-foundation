<?php

use \PHPUnit\Framework\TestCase;
use \extas\interfaces\plugins\IPluginRepository;
use \extas\components\plugins\PluginLog;

class PluginLogTest extends TestCase
{
    protected ?IPluginRepository $pluginRepo = null;

    protected function setUp(): void
    {
        parent::setUp();
        $env = \Dotenv\Dotenv::create(getcwd() . '/tests/');
        $env->load();

        /**
         * For faster operations PluginRepository caches plugins->stage map in memory.
         * But we are creating new plugins runtime, so we need to have possibility to reload memory cache.
         */
        $this->pluginRepo = new class extends \extas\components\plugins\PluginRepository {
            public function reload()
            {
                parent::$stagesWithPlugins = [];
            }
        };
    }

    public function testGetLog()
    {
        $pluginLog = new class extends PluginLog {
            public static function reset()
            {
                static::$pluginLog = [];
            }
        };
        $pluginLog::reset();
        $emptyLog = [
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

        $this->assertEquals($emptyLog, $pluginLog::getLog());
    }

    public function testGetLogIndex()
    {
        $pluginLog = new class extends PluginLog {
            public static function reset()
            {
                static::$pluginLog = [];
            }
        };
        $pluginLog::reset();
        $this->assertEquals(0, $pluginLog::getLogIndex());
    }

    public function testLog()
    {
        $pluginLog = new class extends PluginLog {
            public static function reset()
            {
                static::$pluginLog = [];
            }
        };
        $pluginLog::reset();
        $pluginLog::log($this, 'not.existing.stage');
        $must = [
            'count' => [
                'bs' => [
                    'nto.existing.stage' => 1
                ],
                'bst' => 1,
                'pc' => [],
                'pct' => 0,
                'rc' => [
                    static::class => 1
                ],
                'rct' => 1
            ],
            'log' => [
                [
                    'stage' => 'not.existing.stage',
                    'riser' => static::class,
                    'plugins_count' => 0,
                    'plugins' => []
                ]
            ]
        ];
        $this->assertEquals($must, $pluginLog::getLog());
    }

    public function testLogPluginStage()
    {
        $pluginLog = new class extends PluginLog {
            public static function reset()
            {
                static::$pluginLog = [];
            }
        };
        $pluginLog::reset();
        $pluginLog::logPluginStage('not.existing.stage');
        $must = [
            'count' => [
                'bs' => [
                    'nto.existing.stage' => 1
                ],
                'bst' => 1,
                'pc' => [],
                'pct' => 0,
                'rc' => [],
                'rct' => 0
            ],
            'log' => []
        ];
        $this->assertEquals($must, $pluginLog::getLog());
    }

    public function testLogPluginRiser()
    {
        $pluginLog = new class extends PluginLog {
            public static function reset()
            {
                static::$pluginLog = [];
            }
        };
        $pluginLog::reset();
        $pluginLog::logPluginRiser($this);
        $must = [
            'count' => [
                'bs' => [],
                'bst' => 0,
                'pc' => [],
                'pct' => 0,
                'rc' => [
                    static::class => 1
                ],
                'rct' => 1
            ],
            'log' => []
        ];
        $this->assertEquals($must, $pluginLog::getLog());
    }

    public function testLogPluginClass()
    {
        $pluginLog = new class extends PluginLog {
            public static function reset()
            {
                static::$pluginLog = [];
            }
        };
        $pluginLog::reset();
        $index = $pluginLog::log($this, 'not.existing.stage');
        $pluginLog::logPluginClass('Not\\Existing\\Plugin', $index);
        $must = [
            'count' => [
                'bs' => [
                    'nto.existing.stage' => 1
                ],
                'bst' => 1,
                'pc' => [
                    'Not\\Existing\\Plugin' => 1
                ],
                'pct' => 1,
                'rc' => [
                    static::class => 1
                ],
                'rct' => 1
            ],
            'log' => [
                [
                    'stage' => 'not.existing.stage',
                    'riser' => static::class,
                    'plugins_count' => 1,
                    'plugins' => [
                        'Not\\Existing\\Plugin'
                    ]
                ]
            ]
        ];
        $this->assertEquals($must, $pluginLog::getLog());
    }
}
