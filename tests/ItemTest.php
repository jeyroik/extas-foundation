<?php

use \PHPUnit\Framework\TestCase;
use extas\interfaces\repositories\IRepository;
use extas\components\plugins\Plugin;
use extas\components\SystemContainer;
use extas\components\Item;
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
        $this->pluginRepo = new class extends \extas\components\plugins\PluginRepository {
            public function reload()
            {
                parent::$stagesWithPlugins = [];
            }
        };
        $this->stageRepo = SystemContainer::getItem(IStageRepository::class);
    }

    /**
     * Clean up
     */
    public function tearDown(): void
    {
        $this->pluginRepo->delete([Plugin::FIELD__CLASS => 'NotExistingClass']);
        $this->stageRepo->delete([IStage::FIELD__HAS_PLUGINS => true]);
    }
    
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

    public function testAllowRuntimeSet()
    {
        $child = new class extends \extas\components\Item {
            protected function getSubjectForExtension(): string
            {
                return 'test.child';
            }
        };
        $child->name = 'child';
        $child->type = 'test';

        $this->assertEquals(
            [
                'name' => 'child',
                'type' => 'test'
            ],
            $child->__toArray()
        );

        $this->assertEquals('child', $child->name);
    }

    public function testIssetIsWorking()
    {
        $child = new class(['name' => 'child']) extends \extas\components\Item {
            protected function getSubjectForExtension(): string
            {
                return 'test.child';
            }
        };

        $this->assertEquals(true, isset($child['name']));
    }

    public function testAllowUnsetProperty()
    {
        $child = new class(['name' => 'child']) extends \extas\components\Item {
            protected function getSubjectForExtension(): string
            {
                return 'test.child';
            }
        };
        unset($child['child']);
        $this->assertEquals(false, isset($child['child']));
    }

    public function testStageEntityInitIsRising()
    {
        $this->createPluginAndStage('init', 'Init');
        $this->pluginRepo->reload();
        $this->expectExceptionMessage('Class \'NotExistingClassInit\' not found');
        new class extends Item {
            protected function getSubjectForExtension(): string
            {
                return 'test.child';
            }
        };
    }

    public function testStageEntityInitIsNotRising()
    {
        $this->createPluginAndStage('init', 'Init');
        $this->pluginRepo->reload();
        $this->expectOutputString('Worked');
        new class extends Item {
            protected bool $isAllowInitStage = false;
            public function __construct($config = [])
            {
                parent::__construct($config);
                echo 'Worked';
            }

            protected function getSubjectForExtension(): string
            {
                return 'test.child';
            }
        };
    }

    public function testStageEntityAfterIsRising()
    {
        $this->createPluginAndStage('after', 'After');
        $this->pluginRepo->reload();
        $child = new class extends Item {
            protected function getSubjectForExtension(): string
            {
                return 'test.child';
            }
        };
        $this->expectExceptionMessage('Class \'NotExistingClassAfter\' not found');
        unset($child);
    }

    public function testStageEntityAfterIsNotRising()
    {
        $this->createPluginAndStage('after', 'After');
        $this->pluginRepo->reload();
        $child = new class extends Item {
            protected bool $isAllowAfterStage = false;
            public function __destruct()
            {
                parent::__destruct();
                echo 'Worked';
            }

            protected function getSubjectForExtension(): string
            {
                return 'test.child';
            }
        };
        unset($child);

        $this->expectOutputString('Worked');
    }

    public function testStageEntityToArrayIsRising()
    {
        $this->createPluginAndStage('to.array', 'ToArray');
        $this->pluginRepo->reload();
        $this->expectExceptionMessage('Class \'NotExistingClassToArray\' not found');
        $child = new class extends Item {
            protected function getSubjectForExtension(): string
            {
                return 'test.child';
            }
        };
        $child->__toArray();
    }

    public function testStageEntityToArrayIsNotRising()
    {
        $this->createPluginAndStage('to.array', 'ToArray');
        $this->pluginRepo->reload();
        $child = new class([]) extends Item {
            protected bool $isAllowToArrayStage = false;

            protected function getSubjectForExtension(): string
            {
                return 'test.child';
            }
        };
        $this->assertEquals([], $child->__toArray());
    }

    public function testStageEntityToStringIsRising()
    {
        $this->createPluginAndStage('to.string', 'ToString');
        $this->pluginRepo->reload();
        $this->expectExceptionMessage('Class \'NotExistingClassToString\' not found');
        $child = new class extends Item {
            protected function getSubjectForExtension(): string
            {
                return 'test.child';
            }
        };

        $empty = (string) $child;
    }

    public function testStageEntityToStringIsNotRising()
    {
        $this->createPluginAndStage('to.string', 'ToString');
        $this->pluginRepo->reload();
        $child = new class extends Item {
            protected bool $isAllowToStringStage = false;
            protected function getSubjectForExtension(): string
            {
                return 'test.child';
            }
        };
        $this->assertEquals('', (string) $child);
    }

    public function testStageEntityToIntIsRising()
    {
        $this->createPluginAndStage('to.int', 'ToInt');
        $this->pluginRepo->reload();
        $this->expectExceptionMessage('Class \'NotExistingClassToInt\' not found');
        $child = new class extends Item {
            protected function getSubjectForExtension(): string
            {
                return 'test.child';
            }
        };

        $child->__toInt();
    }

    public function testStageEntityToIntIsNotRising()
    {
        $this->createPluginAndStage('to.int', 'ToInt');
        $this->pluginRepo->reload();
        $child = new class extends Item {
            protected bool $isAllowToIntStage = false;
            protected function getSubjectForExtension(): string
            {
                return 'test.child';
            }
        };
        $this->assertEquals(0, $child->__toInt());
    }

    /**
     * Create plugin and stage records.
     *
     * @param string $stageSuffix
     * @param string $pluginSuffix
     */
    protected function createPluginAndStage(string $stageSuffix, string $pluginSuffix)
    {
        $plugin = new Plugin([
            Plugin::FIELD__CLASS => 'NotExistingClass' . $pluginSuffix,
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
