<?php

use extas\interfaces\repositories\IRepository;
use extas\components\plugins\Plugin;
use extas\components\SystemContainer;
use extas\components\Item;
use extas\interfaces\plugins\IPluginRepository;

/**
 * Class PluginTest
 * @author jeyroik <jeyroik@gmail.com>
 */
class PluginTest extends \PHPUnit\Framework\TestCase
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

    public function testRecallsOnStageInit()
    {
        $plugin = new Plugin([
            Plugin::FIELD__CLASS => 'NotExistingClass',
            Plugin::FIELD__STAGE => 'test.child.init'
        ]);
        $this->repo->create($plugin);
        $this->expectException(\Exception::class);
        $child = new class extends Item {
            protected function getSubjectForExtension(): string
            {
                return 'test.child';
            }
        };
        $this->repo->delete([Plugin::FIELD__STAGE => 'NotExistingClass']);
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
        $this->expectException(\Exception::class);
        $child = new class extends Item {
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
        $this->expectException(\Exception::class);
        $child = new class extends Item {
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
        $this->expectException(\Exception::class);
        $child = new class extends Item {
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
        $this->expectException(\Exception::class);
        $child = new class extends Item {
            protected function getSubjectForExtension(): string
            {
                return 'test.child';
            }
        };
    }
}
