<?php

use \PHPUnit\Framework\TestCase;
use \extas\interfaces\plugins\IPluginRepository;
use \extas\components\plugins\PluginLog;

/**
 * Class PluginLogTest
 * @author jeyroik <jeyroik@gmail.com>
 */
class PluginLogTest extends TestCase
{
    protected ?IPluginRepository $pluginRepo = null;

    protected function setUp(): void
    {
        $this->markTestSkipped('This test is not updated to the Foundation v6');
        parent::setUp();
        $env = \Dotenv\Dotenv::create(getcwd() . '/tests/');
        $env->load();
    }

    public function testGetLog()
    {
        $pluginLog = $this->getPluginLog();
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
        $pluginLog = $this->getPluginLog();
        $pluginLog::reset();
        $this->assertEquals(0, $pluginLog::getLogIndex());
    }

    public function testLog()
    {
        $pluginLog = $this->getPluginLog();
        $pluginLog::reset();
        $pluginLog::log($this, 'not.existing.stage');
        $must = [
            'count' => [
                'bs' => [
                    'not.existing.stage' => 1
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
        $pluginLog = $this->getPluginLog();
        $pluginLog::reset();
        $pluginLog::logPluginStage('not.existing.stage');
        $must = [
            'count' => [
                'bs' => [
                    'not.existing.stage' => 1
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
        $pluginLog = $this->getPluginLog();
        $pluginLog::reset();
        $pluginLog::logPluginRiser(static::class);
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
        $pluginLog = $this->getPluginLog();
        $pluginLog::reset();
        $index = $pluginLog::log($this, 'not.existing.stage');
        $pluginLog::logPluginClass('Not\\Existing\\Plugin', $index);
        $must = [
            'count' => [
                'bs' => [
                    'not.existing.stage' => 1
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

    protected function getPluginLog()
    {
        return new class extends PluginLog {
            public static function reset()
            {
                static::$pluginLog = [
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
            }
        };
    }
}
