<?php

use extas\components\collections\TCollection;
use extas\components\Item;
use extas\components\repositories\TSnuffRepository;
use extas\interfaces\collections\ICollection;
use \PHPUnit\Framework\TestCase;

/**
 * Class CollectionTest
 * @author jeyroik <jeyroik@gmail.com>
 */
class CollectionTest extends TestCase
{
    use TSnuffRepository;

    protected function setUp(): void
    {
        putenv("EXTAS__CONTAINER_PATH_STORAGE_LOCK=resources/container.dist.json");
        $this->buildBasicRepos();
    }

    protected function tearDown(): void
    {
        $this->dropDatabase(__DIR__);
        $this->deleteRepo('plugins');
        $this->deleteRepo('extensions');
    }

    public function testGetOne()
    {
        $collection = new class ([
            'i1' => ['name' => 'n1'],
            'i2' => ['name' => 'n2']
        ]) extends Item implements ICollection {
            use TCollection;

            protected function getSubjectForExtension(): string
            {
                return 'test';
            }
        };

        $this->assertEquals(['name' => 'n1'], $collection->getOne('i1'));
        $this->assertEquals(['missed' => 'true'], $collection->getOne('i3', ['missed' => 'true']));
    }

    public function testHasOne()
    {
        $collection = new class ([
            'i1' => ['name' => 'n1'],
            'i2' => ['name' => 'n2']
        ]) extends Item implements ICollection {
            use TCollection;

            protected function getSubjectForExtension(): string
            {
                return 'test';
            }
        };

        $this->assertTrue($collection->hasOne('i1'));
        $this->expectExceptionMessage('Missed or unknown parameter "i3"');
        $collection->hasOne('i3', true);
    }

    public function testAddOne()
    {
        $collection = new class ([
            'i1' => ['name' => 'n1'],
            'i2' => ['name' => 'n2']
        ]) extends Item implements ICollection {
            use TCollection;

            protected function getSubjectForExtension(): string
            {
                return 'test';
            }
        };

        $this->assertTrue($collection->addOne('i3', ['name' => 'n3']));

        $this->expectExceptionMessage('Parameter "i3" already exists');
        $collection->addOne('i3', []);
    }

    public function testReplaceOne()
    {
        $collection = new class ([
            'i1' => ['name' => 'n1'],
            'i2' => ['name' => 'n2']
        ]) extends Item implements ICollection {
            use TCollection;

            protected function getSubjectForExtension(): string
            {
                return 'test';
            }
        };

        $this->assertTrue($collection->replaceOne('i1', ['name' => 'n3']));
        $this->assertEquals(['name' => 'n3'], $collection->getOne('i1'));
    }

    public function testRemoveOne()
    {
        $collection = new class ([
            'i1' => ['name' => 'n1'],
            'i2' => ['name' => 'n2']
        ]) extends Item implements ICollection {
            use TCollection;

            protected function getSubjectForExtension(): string
            {
                return 'test';
            }
        };

        $this->assertTrue($collection->removeOne('i1'));
        $this->assertFalse($collection->hasOne('i1'));
        $this->assertFalse($collection->removeOne('i1'));
    }

    public function testGetAll()
    {
        $collection = new class ([
            'i1' => ['name' => 'n1'],
            'i2' => ['name' => 'n2']
        ]) extends Item implements ICollection {
            use TCollection;

            protected function getSubjectForExtension(): string
            {
                return 'test';
            }
        };

        $this->assertEquals([
            'i1' => ['name' => 'n1'],
            'i2' => ['name' => 'n2']
        ], $collection->getAll());
        
        $this->assertEquals(['i1' => ['name' => 'n1']], $collection->getAll(['i1']));
        $this->assertEquals(['i1' => ['name' => 'n1'], 'i3' => ['missed' => 'true']], $collection->getAll(['i1', 'i3'], ['missed' => true]));
    }

    public function testHasAll()
    {
        $collection = new class ([
            'i1' => ['name' => 'n1'],
            'i2' => ['name' => 'n2']
        ]) extends Item implements ICollection {
            use TCollection;

            protected function getSubjectForExtension(): string
            {
                return 'test';
            }
        };

        $this->assertTrue($collection->hasAll());
        $this->assertTrue($collection->hasAll(['i1']));
        $this->assertFalse($collection->hasAll(['i1', 'i3']));
    }

    public function testAddAll()
    {
        $collection = new class ([
            'i1' => ['name' => 'n1'],
            'i2' => ['name' => 'n2']
        ]) extends Item implements ICollection {
            use TCollection;

            protected function getSubjectForExtension(): string
            {
                return 'test';
            }
        };

        $this->assertTrue($collection->addAll(['i3' => ['new' => 'true']]));
    }

    public function testReplaceAll()
    {
        $collection = new class ([
            'i1' => ['name' => 'n1'],
            'i2' => ['name' => 'n2']
        ]) extends Item implements ICollection {
            use TCollection;

            protected function getSubjectForExtension(): string
            {
                return 'test';
            }
        };

        $this->assertTrue($collection->replaceAll(['i1' => ['replaced' => 'true']]));
        $this->assertEquals(['i1' => ['replaced' => 'true']], $collection->getAll(['i1']));
    }

    public function testRemoveAll()
    {
        $collection = new class ([
            'i1' => ['name' => 'n1'],
            'i2' => ['name' => 'n2']
        ]) extends Item implements ICollection {
            use TCollection;

            protected function getSubjectForExtension(): string
            {
                return 'test';
            }
        };

        $this->assertTrue($collection->removeAll('i1', 'i2'));
        $this->assertEmpty($collection->getAll());
    }
}
