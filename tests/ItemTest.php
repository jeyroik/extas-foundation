<?php

use \PHPUnit\Framework\TestCase;
use extas\interfaces\repositories\IRepository;
use extas\components\plugins\Plugin;
use extas\components\SystemContainer;
use extas\components\Item;
use extas\interfaces\plugins\IPluginRepository;
use extas\interfaces\stages\IStageRepository;
use extas\components\stages\Stage;
use extas\interfaces\stages\IStage;

/**
 * Class ItemTest
 * @author jeyroik <jeyroik@gmail.com>
 */
class ItemTest extends TestCase
{
    /**
     * @var IRepository|mixed|null
     */
    protected ?IRepository $pluginRepo = null;
    protected ?IRepository $stageRepo = null;

    protected function setUp(): void
    {
        parent::setUp();
        $env = \Dotenv\Dotenv::create(getcwd() . '/tests/');
        $env->load();

        $this->pluginRepo = new class extends \extas\components\plugins\PluginRepository {
            public function reload()
            {
                parent::$stagesWithPlugins = [];
            }
        };
        $this->stageRepo = SystemContainer::getItem(IStageRepository::class);
    }

    public static function tearDownAfterClass(): void
    {
        $repo = SystemContainer::getItem(IPluginRepository::class);
        $repo->delete([Plugin::FIELD__CLASS => 'NotExistingClass']);

        $repo = SystemContainer::getItem(IStageRepository::class);
        $repo->delete([IStage::FIELD__HAS_PLUGINS => true]);
    }

    /**
     * Test default item configuration.
     */
    public function testCorrectAppliesConfigOnConstruct(): void
    {
        $must = [
            'name' => 'child',
            'type' => 'test'
        ];

        $child = new class($must) extends \extas\components\Item {
            protected function getSubjectForExtension(): string
            {
                return 'test.child';
            }
        };

        $this->assertEquals('child', $child['name']);
        $this->assertEquals('child', $child->name);

        foreach ($child as $prop => $value) {
            if (isset($must[$prop])) {
                $this->assertEquals($must[$prop], $value);
            }
        }

        $this->assertEquals($must, $child->__toArray());
    }

    public function testRecallsOnStageInit()
    {
        $this->createPluginAndStage('init');
        $this->pluginRepo->reload();
        $this->expectExceptionMessage('Class \'NotExistingClass\' not found');
        $child = new class extends Item {
            protected function getSubjectForExtension(): string
            {
                return 'test.child';
            }
        };
    }

    public function testRecallsOnStageAfter()
    {
        $this->createPluginAndStage('after');
        $this->pluginRepo->reload();
        $this->expectExceptionMessage('Class \'NotExistingClass\' not found');
        new class extends Item {
            protected function getSubjectForExtension(): string
            {
                return 'test.child';
            }
        };
    }

    public function testRecallsOnStageToArray()
    {
        $this->createPluginAndStage('to.array');
        $this->pluginRepo->reload();
        $this->expectExceptionMessage('Class \'NotExistingClass\' not found');
        new class extends Item {
            protected function getSubjectForExtension(): string
            {
                return 'test.child';
            }
        };
    }

    public function testRecallsOnStageToString()
    {
        $this->createPluginAndStage('to.string');
        $this->pluginRepo->reload();
        $this->expectExceptionMessage('Class \'NotExistingClass\' not found');
        new class extends Item {
            protected function getSubjectForExtension(): string
            {
                return 'test.child';
            }
        };
    }

    public function testRecallsOnStageToInt()
    {
        $this->createPluginAndStage('to.int');
        $this->pluginRepo->reload();
        $this->expectExceptionMessage('Class \'NotExistingClass\' not found');
        new class extends Item {
            protected function getSubjectForExtension(): string
            {
                return 'test.child';
            }
        };
    }

    protected function createPluginAndStage(string $stageSuffix)
    {
        $plugin = new Plugin([
            Plugin::FIELD__CLASS => 'NotExistingClass',
            Plugin::FIELD__STAGE => 'test.child.' . $stageSuffix
        ]);
        $this->pluginRepo->create($plugin);
        $stage = new Stage([
            Stage::FIELD__NAME => 'test.child.' . $stageSuffix,
            Stage::FIELD__HAS_PLUGINS => true
        ]);
        $this->stageRepo->create($stage);
    }
}
