<?php

use \PHPUnit\Framework\TestCase;
use \extas\components\plugins\Plugin;
use \extas\interfaces\stages\IStageRepository;
use \extas\components\stages\StageRepository;
use \extas\components\stages\Stage;
use \extas\components\extensions\Extension;
use \extas\components\plugins\PluginRepository;

/**
 * Class PluginRepositoryTest
 *
 * @author jeyroik <jeyroik@gmail.com>
 */
class PluginRepositoryTest extends TestCase
{
    protected ?IStageRepository $stageRepo = null;
    protected ?PluginRepository $pluginRepo = null;

    protected function setUp(): void
    {
        parent::setUp();
        $env = \Dotenv\Dotenv::create(getcwd() . '/tests/');
        $env->load();
        $this->stageRepo = new StageRepository();
        $this->pluginRepo = new class extends PluginRepository {
            public function reload()
            {
                parent::$stagesWithPlugins = [];
            }

            public function getStageWithPlugins(): array
            {
                return static::$stagesWithPlugins;
            }
        };
    }

    /**
     * Clean up
     */
    public function tearDown(): void
    {
        $this->pluginRepo->delete([Plugin::FIELD__CLASS => Extension::class]);
        $this->stageRepo->delete([Stage::FIELD__NAME => 'not.existing.stage']);
    }

    public function testGetStagePlugins()
    {
        $this->pluginRepo->reload();

        $this->pluginRepo->create(new Plugin([
            Plugin::FIELD__STAGE => 'not.existing.stage',
            Plugin::FIELD__CLASS => Extension::class
        ]));

        $this->stageRepo->create(new Stage([
            Stage::FIELD__NAME => 'not.existing.stage',
            Stage::FIELD__HAS_PLUGINS => true
        ]));

        $correctPlugin = null;
        foreach ($this->pluginRepo->getStagePlugins('not.existing.stage') as $plugin) {
            $this->assertEquals(Extension::class, get_class($plugin));
            $correctPlugin = $plugin;
        }

        $must = [
            'not.existing.stage' => [$correctPlugin],
            'extas.extension.init' => [] // this one, cause we are using Extension class as Plugin
        ];

        $this->assertEquals($must, $this->pluginRepo->getStageWithPlugins());
    }
}
