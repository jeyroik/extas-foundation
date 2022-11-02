<?php
namespace tests\stages;

use extas\components\crawlers\CrawlerStorage;
use extas\components\installers\InstallerStorage;
use extas\components\SystemContainer;
use PHPUnit\Framework\TestCase;
use tests\resources\TBuildRepository;

/**
 * Class InstallerStorageTest
 *
 * @package tests\stages
 * @author jeyroik <jeyroik@gmail.com>
 */
class InstallerStorageTest extends TestCase
{
    use TBuildRepository;

    protected string $basePath = '';

    protected function setUp(): void
    {
        parent::setUp();

        $this->basePath = __DIR__ . '/../tmp_install';
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
        $crawler = new CrawlerStorage('extas.app.storage.test.json', 'extas.storage.test.json');
        list($app, $packages) = $crawler(__DIR__ . '/../resources');

        $installer = new InstallerStorage($app, $packages);

        mkdir($this->basePath, 0777);

        $installer->install($this->basePath, __DIR__ . '/../../resources');

        $this->assertFileExists($this->basePath . '/RepositoryPlugins.php', 'Missed RepositoryPlugins');
        $this->assertFileExists($this->basePath . '/RepositoryExtensions.php', 'Missed RepositoryExtensions');
        $this->assertFileExists($this->basePath . '/RepositoryEntries.php', 'Missed RepositoryEntries');

        $this->assertStringContainsString(
            'DriverFileJson',
            file_get_contents($this->basePath . '/RepositoryEntries.php'),
            'Incorrect driver'
        );

        $plugins = SystemContainer::getItem('plugins');
        $items = $plugins->all(['class' => 'testP']);
        $this->assertCount(1, $items, 'Too much or missed plugins with class = testP');

        $extensions = SystemContainer::getItem('extensions');
        $items = $extensions->all(['class' => 'testE']);
        $this->assertCount(1, $items, 'Too much or missed extensions with class = testE');

        // entries - entities, they are placed in another config
        $entries = SystemContainer::getItem('entries');
        $items = $entries->all([]);
        $this->assertCount(0, $items, 'Too much or missed entries with class = testE');

        $repos = ['plugins', 'extensions', 'entries'];
        foreach ($repos as $repoName) {
            $repo = SystemContainer::getItem($repoName);
            $repo->drop();
        }
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
