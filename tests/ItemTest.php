<?php

use \PHPUnit\Framework\TestCase;
use extas\interfaces\repositories\IRepository;
use extas\components\plugins\Plugin;
use extas\components\SystemContainer;
use extas\components\Item;
use extas\interfaces\plugins\IPluginRepository;

/**
 * Class ItemTest
 * @author jeyroik <jeyroik@gmail.com>
 */
class ItemTest extends TestCase
{
    /**
     * @var IRepository|mixed|null
     */
    protected ?IRepository $repo = null;

    protected function setUp(): void
    {
        parent::setUp();
        $env = \Dotenv\Dotenv::create(getcwd() . '/tests/');
        $env->load();

        $this->repo = SystemContainer::getItem(
            IPluginRepository::class
        );
    }

    /**
     * Delete created plugins
     */
    protected function tearDown(): void
    {
        parent::tearDown();
        $this->repo->delete([Plugin::FIELD__STAGE => 'NotExistingClass']);
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
        $plugin = new Plugin([
            Plugin::FIELD__CLASS => 'NotExistingClass',
            Plugin::FIELD__STAGE => 'test.child.init'
        ]);
        $this->repo->create($plugin);
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
        /**
         * @var $repo IPluginRepository
         */
        $repo = SystemContainer::getItem(IPluginRepository::class);
        $plugin = new Plugin([
            Plugin::FIELD__CLASS => 'NotExistingClass',
            Plugin::FIELD__STAGE => 'test.child.after'
        ]);
        $repo->create($plugin);
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
        /**
         * @var $repo IPluginRepository
         */
        $repo = SystemContainer::getItem(IPluginRepository::class);
        $plugin = new Plugin([
            Plugin::FIELD__CLASS => 'NotExistingClass',
            Plugin::FIELD__STAGE => 'test.child.to.array'
        ]);
        $repo->create($plugin);
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
        /**
         * @var $repo IPluginRepository
         */
        $repo = SystemContainer::getItem(IPluginRepository::class);
        $plugin = new Plugin([
            Plugin::FIELD__CLASS => 'NotExistingClass',
            Plugin::FIELD__STAGE => 'test.child.to.string'
        ]);
        $repo->create($plugin);
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
        /**
         * @var $repo IPluginRepository
         */
        $repo = SystemContainer::getItem(IPluginRepository::class);
        $plugin = new Plugin([
            Plugin::FIELD__CLASS => 'NotExistingClass',
            Plugin::FIELD__STAGE => 'test.child.to.int'
        ]);
        $repo->create($plugin);
        $this->expectError();
        new class extends Item {
            protected function getSubjectForExtension(): string
            {
                return 'test.child';
            }
        };
    }
}
