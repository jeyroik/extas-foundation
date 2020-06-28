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
    protected ?PluginRepository $pluginRepo = null;

    protected function setUp(): void
    {
        parent::setUp();
        $env = \Dotenv\Dotenv::create(getcwd() . '/tests/');
        $env->load();
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
        $this->pluginRepo->delete([Plugin::FIELD__STAGE => 'not.existing.stage']);
    }

    public function testGetStagePlugins()
    {
        $this->pluginRepo->reload();

        $plugin1 = new Plugin([
            Plugin::FIELD__STAGE => 'not.existing.stage',
            Plugin::FIELD__CLASS => Extension::class
        ]);
        $this->pluginRepo->create($plugin1);
        foreach ($this->pluginRepo->getStagePlugins('not.existing.stage') as $plugin) {
            $this->assertEquals(Extension::class, get_class($plugin));
        }
        $hash = sha1(json_encode([]));

        $must = [
            'not.existing.stage'.$hash => [$plugin1],
            'extas.extension.init'.$hash => [] // this one, cause we are using Extension class as Plugin
        ];

        $this->assertEquals($must, $this->pluginRepo->getStageWithPlugins());
    }

    public function testGetPluginsByPriority()
    {
        $this->pluginRepo->reload();

        $plugin1 = new Plugin([
            Plugin::FIELD__STAGE => 'not.existing.stage',
            Plugin::FIELD__CLASS => Extension::class,
            Plugin::FIELD__PRIORITY => 1
        ]);
        $this->pluginRepo->create($plugin1);

        $plugin2 = new Plugin([
            Plugin::FIELD__STAGE => 'not.existing.stage',
            Plugin::FIELD__CLASS => Plugin::class,
            Plugin::FIELD__PRIORITY => 10
        ]);
        $this->pluginRepo->create($plugin2);

        $count = 0;
        foreach ($this->pluginRepo->getStagePlugins('not.existing.stage') as $plugin) {
            if ($count == 0) {
                $this->assertEquals(Plugin::class, get_class($plugin));
                $count++;
            } else {
                $this->assertEquals(Extension::class, get_class($plugin));
            }
        }

        $hash = sha1(json_encode([]));
        $must = [
            'not.existing.stage'.$hash => [$plugin2, $plugin1], // by priority
            'extas.extension.init'.$hash => [], // this one, cause we are using Extension class as Plugin
            'extas.extension.after'.$hash => [] // this one, cause we are using Extension class as Plugin
        ];

        $this->assertEquals($must, $this->pluginRepo->getStageWithPlugins());
    }
}
