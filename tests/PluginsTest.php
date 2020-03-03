<?php

use \PHPUnit\Framework\TestCase;
use \extas\components\plugins\Plugin;
use \extas\components\plugins\PluginRepository;
use extas\components\SystemContainer;
use extas\interfaces\stages\IStageRepository;
use extas\interfaces\stages\IStage;
use extas\components\stages\Stage;
use \extas\components\plugins\PluginLog;
use \extas\components\Plugins;
use extas\interfaces\repositories\IRepository;

/**
 * Class PluginsTest
 *
 * @author jeyroik@gmail.com
 */
class PluginsTest extends TestCase
{
    /**
     * @var IRepository|null
     */
    protected ?IRepository $pluginRepo = null;

    /**
     * @var IRepository|null
     */
    protected ?IRepository $stageRepo = null;

    protected function setUp(): void
    {
        parent::setUp();
        $env = \Dotenv\Dotenv::create(getcwd() . '/tests/');
        $env->load();

        /**
         * For faster operations PluginRepository caches plugins->stage map in memory.
         * But we are creating new plugins runtime, so we need to have possibility to reload memory cache.
         */
        $this->pluginRepo = new class extends PluginRepository {
            public function reload()
            {
                parent::$stagesWithPlugins = [];
            }
        };
        $this->stageRepo = SystemContainer::getItem(IStageRepository::class);
    }

    public function tearDown(): void
    {
        $this->pluginRepo->delete([Plugin::FIELD__CLASS => Plugins::class]);
        $this->stageRepo->delete([IStage::FIELD__NAME => 'not.existing.stage']);
    }

    public function testGetPluginsByStageWithoutPassingRiser()
    {
        $this->createPluginAndStage('not.existing.stage', Plugins::class);
        $log = new class extends PluginLog {
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
        $log::reset();

        foreach (Plugins::byStage('not.existing.stage') as $plugin) {
            $this->assertEquals(Plugins::class, get_class($plugin));
        }

        $must = [
            'count' => [
                'bs' => [
                    'not.existing.stage' => 1
                ],
                'bst' => 1,
                'pc' => [
                    Plugins::class => 1
                ],
                'pct' => 1,
                'rc' => [
                    Plugins::class => 1
                ],
                'rct' => 1
            ],
            'log' => [
                [
                    'stage' => 'not.existing.stage',
                    'riser' => Plugins::class,
                    'plugins_count' => 1,
                    'plugins' => [
                        Plugins::class
                    ]
                ]
            ]
        ];

        $this->assertEquals($must, $log::getLog());
    }

    public function testGetPluginsByStageWithPassingRiser()
    {
        $this->createPluginAndStage('not.existing.stage', Plugins::class);
        $log = new class extends PluginLog {
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
        $log::reset();

        foreach (Plugins::byStage('not.existing.stage', $this) as $plugin) {
            $this->assertEquals(Plugins::class, get_class($plugin));
        }

        $must = [
            'count' => [
                'bs' => [
                    'not.existing.stage' => 1
                ],
                'bst' => 1,
                'pc' => [
                    Plugins::class => 1
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
                        Plugins::class
                    ]
                ]
            ]
        ];

        $this->assertEquals($must, $log::getLog());
    }

    /**
     * Create plugin and stage records.
     *
     * @param string $stageName
     * @param string $pluginClass
     */
    protected function createPluginAndStage(string $stageName, string $pluginClass)
    {
        $plugin = new Plugin([
            Plugin::FIELD__CLASS => $pluginClass,
            Plugin::FIELD__STAGE => $stageName
        ]);
        $this->pluginRepo->create($plugin);

        $stage = new Stage([
            Stage::FIELD__NAME => $stageName,
            Stage::FIELD__HAS_PLUGINS => true
        ]);
        $this->stageRepo->create($stage);
    }
}