<?php

use \PHPUnit\Framework\TestCase;
use extas\interfaces\repositories\IRepository;
use extas\components\plugins\Plugin;
use extas\components\Item;
use Dotenv\Dotenv;
use tests\resources\TBuildRepository;

/**
 * Class ItemTest
 * @author jeyroik <jeyroik@gmail.com>
 */
class ItemTest extends TestCase
{
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

    /**
     * Clean up
     */
    public function tearDown(): void
    {
        $this->pluginRepo->drop();
    }
    
    public function testAllowConfigOnConstruct(): void
    {
        $this->pluginRepo->drop();

        $must = [
            'name' => 'child',
            'type' => 'test'
        ];

        $child = new class($must) extends Item {
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
        $child = new class extends Item {
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
        $child = new class(['name' => 'child']) extends Item {
            protected function getSubjectForExtension(): string
            {
                return 'test.child';
            }
        };

        $this->assertEquals(true, isset($child['name']));
    }

    public function testMagicIssetIsWorking()
    {
        $child = new class(['name' => 'child']) extends Item {
            protected function getSubjectForExtension(): string
            {
                return 'test.child';
            }
        };

        $this->assertEquals(['child'], array_column([$child], 'name'));
    }

    public function testMerge()
    {
        $child = new class([
            'name' => 'child',
            'test' => 'is ok'
        ]) extends Item {
            protected function getSubjectForExtension(): string
            {
                return 'test.child';
            }
        };

        $child->__merge(['name' => 'parent', 'new' => 'is ok']);
        $this->assertEquals(
            [
                'name' => 'parent',
                'test' => 'is ok',
                'new' => 'is ok'
            ],
            $child->__toArray()
        );
    }

    public function testSubject()
    {
        $child = new class() extends Item {
            protected function getSubjectForExtension(): string
            {
                return 'test.child';
            }
        };

        $this->assertEquals('test.child', $child->__subject());
    }

    public function testSelect()
    {
        $child = new class([
            'arg0' => 0,
            'arg1' => '1',
            'arg2' => '2'
        ]) extends Item {
            protected function getSubjectForExtension(): string
            {
                return 'test.child';
            }
        };

        $filtered = $child->__select('arg1', 'arg2');

        $this->assertEquals(
            ['arg1' => 1, 'arg2' => 2],
            $filtered->__toArray()
        );

        $this->assertEquals(
            [
                'arg0' => 0,
                'arg1' => '1',
                'arg2' => '2'
            ],
            $child->__toArray()
        );
    }

    public function testHas()
    {
        $child = new class([
            'name' => 'child',
            'test' => 'is ok'
        ]) extends Item {
            protected function getSubjectForExtension(): string
            {
                return 'test.child';
            }
        };

        $this->assertTrue($child->has('name', 'test'));
    }

    public function testAllowUnsetProperty()
    {
        $child = new class(['name' => 'child']) extends Item {
            protected function getSubjectForExtension(): string
            {
                return 'test.child';
            }
        };
        unset($child['child']);
        $this->assertEquals(false, isset($child['child']));
    }

    public function testStageEntityCreatedIsRising()
    {
        $this->pluginRepo->drop();
        $this->createPlugin('created');
        $this->expectExceptionMessage('Missed or unknown class "NotExistingClass"');
        $child = new class extends Item {
            protected function getSubjectForExtension(): string
            {
                return 'test.child';
            }
        };
        $child->__created($child, $this->pluginRepo);
    }

    public function testStageEntityCreatedIsNotRising()
    {
        
        $this->createPlugin('created');
        $this->pluginRepo->drop();
        $this->expectOutputString('Worked');
        $child = new class extends Item {
            public function __created($item, $repo)
            {
                parent::__created($item, $repo);

                echo 'Worked';

                return $this;
            }

            protected function getSubjectForExtension(): string
            {
                return 'test.child';
            }
        };
        $child->__created($child, $this->pluginRepo);
    }

    public function testStageEntityInitIsRising()
    {
        $this->createPlugin('init');
        $this->expectExceptionMessage('Missed or unknown class "NotExistingClass"');
        new class extends Item {
            protected function getSubjectForExtension(): string
            {
                return 'test.child';
            }
        };
    }

    public function testStageEntityInitIsNotRising()
    {
        $this->createPlugin('init');
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
        $this->createPlugin('after');
        $child = new class extends Item {
            protected function getSubjectForExtension(): string
            {
                return 'test.child';
            }
        };
        $this->expectExceptionMessage('Missed or unknown class "NotExistingClass"');
        unset($child);
    }

    public function testStageEntityAfterIsNotRising()
    {
        $this->createPlugin('after');
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
        $this->createPlugin('to.array');
        $this->expectExceptionMessage('Missed or unknown class "NotExistingClass"');
        $child = new class extends Item {
            protected function getSubjectForExtension(): string
            {
                return 'test.child';
            }
        };
        $child->__toArray();
    }

    public function testStageEntityToJsonIsRising()
    {
        $this->createPlugin('to.json');
        $this->expectExceptionMessage('Missed or unknown class "NotExistingClass"');
        $child = new class extends Item {
            protected function getSubjectForExtension(): string
            {
                return 'test.child';
            }
        };
        $child->__toJson();
    }

    public function testStageEntityToJsonIsNotRising()
    {
        $this->createPlugin('to.json');
        $child = new class([]) extends Item {
            protected bool $isAllowToJsonStage = false;

            protected function getSubjectForExtension(): string
            {
                return 'test.child';
            }
        };
        $this->assertEquals(json_encode($child->__toArray()), $child->__toJson());
    }

    public function testStageEntityToArrayIsNotRising()
    {
        $this->createPlugin('to.array');
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
        $this->createPlugin('to.string');
        $this->expectExceptionMessage('Missed or unknown class "NotExistingClass"');
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
        $this->createPlugin('to.string');
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
        $this->createPlugin('to.int');
        $this->expectExceptionMessage('Missed or unknown class "NotExistingClass"');
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
        $this->createPlugin('to.int');
        $child = new class extends Item {
            protected bool $isAllowToIntStage = false;
            protected function getSubjectForExtension(): string
            {
                return 'test.child';
            }
        };
        $this->assertEquals(0, $child->__toInt());
    }

    public function testEqual()
    {
        $object1 = new class ([
            'test' => 1
        ]) extends Item {
            protected function getSubjectForExtension(): string
            {
                return '';
            }
        };

        $object2 = new class ([
            'test' => 1
        ]) extends Item {
            protected function getSubjectForExtension(): string
            {
                return '';
            }
        };

        $this->assertTrue($object1->__equal($object2));

        $object3 = new class ([
            'test' => 2
        ]) extends Item {
            protected function getSubjectForExtension(): string
            {
                return '';
            }
        };

        $this->assertFalse($object1->__equal($object3));

        $object4 = new class ([
            'test_1' => 1
        ]) extends Item {
            protected function getSubjectForExtension(): string
            {
                return '';
            }
        };

        $this->assertFalse($object1->__equal($object4));

        $object5 = new class ([
            'test' => 1,
            'test2' => 1
        ]) extends Item {
            protected function getSubjectForExtension(): string
            {
                return '';
            }
        };

        $this->assertFalse($object1->__equal($object5));
        $this->assertFalse($object5->__equal($object1));
    }

    /**
     * Create plugin and stage records.
     *
     * @param string $stageSuffix
     */
    protected function createPlugin(string $stageSuffix)
    {
        $plugin = new Plugin([
            Plugin::FIELD__CLASS => 'NotExistingClass',
            Plugin::FIELD__STAGE => 'test.child.' . $stageSuffix
        ]);
        $this->pluginRepo->create($plugin);
    }
}
