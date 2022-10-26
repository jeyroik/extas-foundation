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
        parent::setUp();
        $env = Dotenv::create(getcwd() . '/tests/');
        $env->load();

        $this->pluginRepo = $this->buildPluginsRepo();
    }

    public function tearDown(): void
    {
        $this->dropDatabase();
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

        foreach (Plugins::byStage('not.existing.stage') as $plugin) {
            $this->assertEquals(Plugins::class, get_class($plugin));
        }
    }

    public function testGetPluginsByStageWithPassingRiser()
    {
        $this->createPlugin('not.existing.stage', Plugins::class);

        foreach (Plugins::byStage('not.existing.stage', $this) as $plugin) {
            $this->assertEquals(Plugins::class, get_class($plugin));
        }
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
