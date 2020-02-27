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

        $this->pluginRepo = SystemContainer::getItem(IPluginRepository::class);
        $this->stageRepo = SystemContainer::getItem(IStageRepository::class);
    }

    /**
     * Delete created plugins
     */
    protected function tearDown(): void
    {
        parent::tearDown();
        $this->pluginRepo->delete([Plugin::FIELD__STAGE => 'NotExistingClass']);
        $this->stageRepo->delete([IStage::FIELD__HAS_PLUGINS => true]);
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
        $this->expectError();
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
        $this->expectError();
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
        $this->expectError();
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
        $this->expectError();
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
        $this->expectError();
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
