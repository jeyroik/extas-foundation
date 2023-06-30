<?php

use \PHPUnit\Framework\TestCase;
use extas\components\plugins\Plugin;
use extas\components\repositories\TSnuffRepository;
use extas\components\SystemContainer;

/**
 * Class DriverFileJsonTest
 *
 * @author jeyroik@gmail.com
 */
class DriverFileJsonTest extends TestCase
{
    use TSnuffRepository;

    protected function setUp(): void
    {
        $this->buildBasicRepos();
    }

    public function tearDown(): void
    {
       $this->dropDatabase();
    }

    public function testInsertAndFind()
    {
        $repo = SystemContainer::getItem('plugins');

        $repo->create(new Plugin([
            Plugin::FIELD__CLASS => 'NotExisting',
            Plugin::FIELD__STAGE => ['not','existing']
        ]));

        $repo->create(new Plugin([
            Plugin::FIELD__CLASS => 'NotExisting2',
            Plugin::FIELD__STAGE => ['not','existing']
        ]));

        $repo->create(new Plugin([
            Plugin::FIELD__CLASS => 'NotExisting3',
            Plugin::FIELD__STAGE => ['not','existing'],
            Plugin::FIELD__PARAMETERS => [
                'test' => 'is ok'
            ]
        ]));

        $plugin = $repo->one([Plugin::FIELD__CLASS => 'NotExisting']);
        $this->assertNotEmpty($plugin, 'Can not find plugin with class = NotExisting');

        $plugin = $repo->one([Plugin::FIELD__CLASS => ['NotExisting']]);
        $this->assertNotEmpty($plugin, 'Can not find plugin with class in [NotExisting]');

        $plugins = $repo->all([Plugin::FIELD__CLASS => 'NotExisting']);
        $this->assertNotEmpty($plugins, 'Can not find plugin by all()');

        $plugins = $repo->all([Plugin::FIELD__CLASS => ['NotExisting']]);
        $this->assertNotEmpty($plugins, 'Can not find plugin by all() in [NotExisting]');

        $plugin = $repo->one([Plugin::FIELD__STAGE => 'not']);
        $this->assertNotEmpty($plugin, 'Can not find plugin by stage "not"');

        $plugin = $repo->one([Plugin::FIELD__STAGE => ['not']]);
        $this->assertNotEmpty($plugin, 'Can not find plugin by stage in [not]');

        $plugin = $repo->one([Plugin::FIELD__PARAMETERS . '.test' => ['is ok']]);
        $this->assertNotEmpty($plugin, 'Can not find plugin by sub fields');

        $plugins = $repo->all([Plugin::FIELD__STAGE => ['not']], limit: 1, offset: 1);
        $this->assertNotEmpty($plugins, 'Can not find plugin by all(limit=1, offset=1) in [NotExisting]');
        $this->assertCount(1, $plugins, 'Invalid limit & offset working');
        $plugin = array_shift($plugins);
        $this->assertEquals('NotExisting2', $plugin->getClass(), 'Invalid limit & offset working');

        $plugins = $repo->all([Plugin::FIELD__PARAMETERS . '.test' => 'is ok', Plugin::FIELD__STAGE => ['not']]);
        $this->assertCount(1, $plugins, 'Invalid dot fields operating');
    }

    public function testUpdateOne()
    {
        $repo = SystemContainer::getItem('plugins');

        $plugin = new Plugin([
            Plugin::FIELD__ID => '1',
            Plugin::FIELD__CLASS => 'NotExisting',
            Plugin::FIELD__STAGE => ['not','existing']
        ]);

        $repo->create($plugin);

        $plugin = $repo->one([Plugin::FIELD__CLASS => 'NotExisting']);
        $this->assertNotEmpty($plugin, 'Can not find plugin with class = NotExisting');

        $plugin->setClass('Existing not today');

        $repo->update($plugin);

        $plugin = $repo->one([Plugin::FIELD__CLASS => 'Existing not today']);
        $this->assertNotEmpty($plugin, 'Can not find plugin with class = Existing not today');
    }

    public function testUpdateMany()
    {
        $repo = SystemContainer::getItem('plugins');

        $plugin = new Plugin([
            Plugin::FIELD__ID => '1',
            Plugin::FIELD__CLASS => 'NotExisting',
            Plugin::FIELD__STAGE => ['not','existing']
        ]);

        $repo->create($plugin);

        $plugin = $repo->one([Plugin::FIELD__CLASS => 'NotExisting']);
        $this->assertNotEmpty($plugin, 'Can not find plugin with class = NotExisting');

        $plugin->setClass('Existing not today');

        $result = $repo->update($plugin, [Plugin::FIELD__CLASS => 'NotExisting']);

        $plugin = $repo->one([Plugin::FIELD__CLASS => 'Existing not today']);
        $this->assertNotEmpty($plugin, 'Can not find plugin with class = Existing not today');

        $this->assertEquals($result, 1);
    }

    public function testDeleteOne()
    {
        $repo = SystemContainer::getItem('plugins');

        $plugin = new Plugin([
            Plugin::FIELD__ID => '1',
            Plugin::FIELD__CLASS => 'NotExisting',
            Plugin::FIELD__STAGE => ['not','existing']
        ]);

        $repo->create($plugin);

        $plugin = $repo->one([Plugin::FIELD__CLASS => 'NotExisting']);
        $this->assertNotEmpty($plugin, 'Can not find plugin with class = NotExisting');

        $repo->delete([], $plugin);

        $plugin = $repo->one([Plugin::FIELD__CLASS => 'NotExisting']);
        $this->assertEmpty($plugin, 'Found plugin with class = NotExisting');
    }

    public function testDeleteMany()
    {
        $repo = SystemContainer::getItem('plugins');

        $plugin = new Plugin([
            Plugin::FIELD__ID => '1',
            Plugin::FIELD__CLASS => 'NotExisting',
            Plugin::FIELD__STAGE => ['not','existing']
        ]);

        $repo->create($plugin);

        $plugin = $repo->one([Plugin::FIELD__CLASS => 'NotExisting']);
        $this->assertNotEmpty($plugin, 'Can not find plugin with class = NotExisting');

        $result = $repo->delete([Plugin::FIELD__CLASS => 'NotExisting']);

        $plugin = $repo->one([Plugin::FIELD__CLASS => 'NotExisting']);
        $this->assertEmpty($plugin, 'Found plugin with class = NotExisting');

        $this->assertEquals($result, 1);
    }

    public function testDrop()
    {
        $repo = SystemContainer::getItem('plugins');

        $plugin = new Plugin([
            Plugin::FIELD__ID => '1',
            Plugin::FIELD__CLASS => 'NotExisting',
            Plugin::FIELD__STAGE => ['not','existing']
        ]);

        $repo->create($plugin);

        $plugin = $repo->one([Plugin::FIELD__CLASS => 'NotExisting']);
        $this->assertNotEmpty($plugin, 'Can not find plugin with class = NotExisting');

        $repo->drop();

        $plugin = $repo->one([Plugin::FIELD__CLASS => 'NotExisting']);
        $this->assertEmpty($plugin, 'Found plugin with class = NotExisting');
    }
}
