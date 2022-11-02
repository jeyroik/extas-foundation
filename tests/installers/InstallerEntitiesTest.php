<?php
namespace tests\stages;

use extas\components\crawlers\CrawlerEntities;
use extas\components\crawlers\CrawlerStorage;
use extas\components\installers\InstallerEntities;
use extas\components\installers\InstallerStorage;
use extas\components\SystemContainer;
use extas\interfaces\stages\IStageBeforeInstallEntity;
use PHPUnit\Framework\TestCase;
use tests\resources\TBuildRepository;

/**
 * Class InstallerStorageTest
 *
 * @package tests\stages
 * @author jeyroik <jeyroik@gmail.com>
 */
class InstallerEntitiesTest extends TestCase
{
    use TBuildRepository;

    protected string $basePath = '';

    protected function setUp(): void
    {
        parent::setUp();

        $this->basePath = __DIR__ . '/../tmp_install';

        $this->cleanDir();
        SystemContainer::refresh();
    }

    /**
     * Clean up
     */
    public function tearDown(): void
    {
        $this->cleanDir();
        $this->dropDatabase();
        SystemContainer::refresh();
    }

    public function testInstalling()
    {
        $this->installStorage();

        $crawler = new CrawlerEntities('extas.app.test.json', 'extas.test.json');
        list($app, $packages) = $crawler(__DIR__ . '/../resources');

        $installer = new InstallerEntities($app, $packages);
        $installer->install();

        // entries - entities, they are placed in another config
        $entries = SystemContainer::getItem('entries');
        $items = $entries->all([]);
        $this->assertCount(2, $items, 'Too much or missed entries with class = testE');

        foreach ($items as $item) {
            $this->assertTrue(
                isset($item['changed']),
                'Missed property "changed". Stage ' . IStageBeforeInstallEntity::NAME . ' is not working'
            );
        }

        $repos = ['plugins', 'extensions', 'entries'];
        foreach ($repos as $repoName) {
            $repo = SystemContainer::getItem($repoName);
            $repo->drop();
        }
    }

    protected function installStorage(): void
    {
        $crawler = new CrawlerStorage('extas.app.storage.test.json', 'extas.storage.test.json');
        list($app, $packages) = $crawler(__DIR__ . '/../resources');

        $installer = new InstallerStorage($app, $packages);

        mkdir($this->basePath, 0777);

        $installer->install($this->basePath, __DIR__ . '/../../resources');
    }

    protected function cleanDir(): void
    {
        if (is_dir($this->basePath)) {
            if (is_file($this->basePath . '/RepositoryPlugins.php')) {
                unlink($this->basePath . '/RepositoryPlugins.php');
            }
            if (is_file($this->basePath . '/RepositoryExtensions.php')) {
                unlink($this->basePath . '/RepositoryExtensions.php');
            }
            if (is_file($this->basePath . '/RepositoryEntries.php')) {
                unlink($this->basePath . '/RepositoryEntries.php');
            }
            rmdir($this->basePath);
        }
    }
}
