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

        $this->assertEquals($emptyLog, PluginLog::getLog());
    }

    public function testGetLogIndex()
    {
        $this->assertEquals(0, PluginLog::getLogIndex());
    }

    public function testLog()
    {
        PluginLog::log($this, 'not.existing.stage');
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
        $this->assertEquals($must, PluginLog::getLog());
    }

    public function testLogPluginStage()
    {
        PluginLog::logPluginStage('not.existing.stage');
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
        $this->assertEquals($must, PluginLog::getLog());
    }

    public function testLogPluginRiser()
    {
        PluginLog::logPluginRiser($this);
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
        $this->assertEquals($must, PluginLog::getLog());
    }

    public function testLogPluginClass()
    {
        $index = PluginLog::log($this, 'not.existing.stage');
        PluginLog::logPluginClass('Not\\Existing\\Plugin', $index);
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
        $this->assertEquals($must, PluginLog::getLog());
    }
}
