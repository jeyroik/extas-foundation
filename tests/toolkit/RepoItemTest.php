<?php

use extas\components\exceptions\AlreadyExist;
use extas\components\exceptions\MissedOrUnknown;
use extas\components\Item;
use extas\components\repositories\RepoItem;
use extas\components\repositories\TSnuffRepository;
use extas\components\THasStringId;
use extas\interfaces\IHaveUUID;
use \PHPUnit\Framework\TestCase;
use tests\resources\AsArray;

/**
 * Class RepoItemTest
 * @author jeyroik <jeyroik@gmail.com>
 */
class RepoItemTest extends TestCase
{
    use TSnuffRepository;

    protected function setUp(): void
    {
        
        $this->buildBasicRepos();
    }

    protected function tearDown(): void
    {
        $this->dropDatabase(__DIR__);
        $this->deleteRepo('plugins');
        $this->deleteRepo('extensions');
    }

    public function testWithRepo(): void
    {
        $this->buildRepo(__DIR__ . '/../../resources', [
            'tests' => [
                'namespace' => 'tests\\tmp',
                'item_class' => 'extas\\components\\items\\SnuffItem',
                'pk' => 'id'
            ]
        ]);

        $obj = new class extends Item{
            protected function getSubjectForExtension(): string
            {
                return '';
            }
        };

        $repo = $obj->tests();
        $obj['test'] = 1;

        $repo->create($obj);

        try {
            RepoItem::throwIfExist($repo, $obj, ['test']);
        } catch(AlreadyExist $e) {
            $this->assertEquals('Tests already exists', $e->getMessage());
        }
        $this->deleteRepo('tests');
    }

    public function testWithoutRepo(): void
    {
        $obj = new AsArray();

        RepoItem::setId($obj);

        $this->assertNotEmpty($obj->getId());
        $this->assertEquals(36, strlen($obj->getId()));

        $obj = new class extends Item{
            protected function getSubjectForExtension(): string
            {
                return '';
            }
        };

        RepoItem::setUuid($obj, 'test');

        $this->assertEquals(36, strlen($obj['test']));

        $obj['sha1'] = 'test';
        $sha1 = sha1('test');

        RepoItem::sha1($obj, ['sha1']);

        $this->assertEquals($obj['sha1'], $sha1);

        $this->expectException(MissedOrUnknown::class);
        RepoItem::throwIfMissedFields($obj, ['test1', 'test2']);
    }

    public function testMultiple(): void
    {
        $obj = new class extends Item implements IHaveUUID {
            use THasStringId;

            protected function getSubjectForExtension(): string
            {
                return '';
            }
        };

        $this->buildRepo(__DIR__ . '/../../resources', [
            'tests' => [
                'namespace' => 'tests\\tmp',
                'item_class' => 'extas\\components\\items\\SnuffItem',
                'pk' => 'id'
            ]
        ]);

        $repo = $obj->tests();

        $obj['test'] = 1;

        RepoItem::multiple($repo, $obj, [
            'setId' => [],
            'setUuid' => ['field' => 'uuid'],
            'throwIfExist' => ['id'],
            'throwIfMissedFields' => ['test'],
            'sha1' => ['test'],
            'unknown' => ['test']
        ]);

        $this->assertEquals($obj['test'], sha1(1));
        $this->assertEquals(36, strlen($obj['id']));
        $this->assertEquals(36, strlen($obj['uuid']));

        $this->deleteRepo('tests');
    }
}
