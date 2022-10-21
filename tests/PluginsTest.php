<?php
namespace tests;

use extas\components\plugins\TSnuffPlugins;
use \PHPUnit\Framework\TestCase;
use \extas\components\plugins\Plugin;
use \extas\components\plugins\PluginLog;
use \extas\components\Plugins;
use extas\interfaces\repositories\IRepository;
use Dotenv\Dotenv;
use tests\resources\TBuildRepository;

/**
 * Class PluginsTest
 *
 * @author jeyroik@gmail.com
 */
class PluginsTest extends TestCase
{
    use TSnuffPlugins;
    use TBuildRepository;

    /**
     * @var IRepository|null
     */
    protected ?IRepository $pluginRepo = null;

    protected function setUp(): void
    {
        $this->markTestSkipped('This test is not updated to the Foundation v6');
        parent::setUp();
        $env = Dotenv::create(getcwd() . '/tests/');
        $env->load();

        $this->pluginRepo = $this->buildPluginsRepo();
    }

    public function tearDown(): void
    {
        $this->pluginRepo->delete([Plugin::FIELD__CLASS => Plugins::class]);
        $this->deleteSnuffPlugins();
        $this->removePluginsRepo();
    }

    public function testPluginConfig()
    {
        $this->createPluginEmpty(['test']);
        foreach (Plugins::byStage('test', $this, ['test' => 'is ok']) as $plugin) {
            $this->assertEquals('is ok', $plugin['test'], 'Plugin missed config param');
        }
    }

    public function testGetPluginsByStageWithoutPassingRiser()
    {
        $this->createPlugin('not.existing.stage', Plugins::class);
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
        $this->createPlugin('not.existing.stage', Plugins::class);
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
    protected function createPlugin(string $stageName, string $pluginClass)
    {
        $plugin = new Plugin([
            Plugin::FIELD__CLASS => $pluginClass,
            Plugin::FIELD__STAGE => $stageName
        ]);
        $this->pluginRepo->create($plugin);
    }
}
