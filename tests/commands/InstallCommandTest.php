<?php
namespace tests\stages;

use extas\components\commands\InstallCommand;
use extas\components\console\TSnuffConsole;
use extas\components\SystemContainer;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Console\Output\BufferedOutput;
use tests\resources\TBuildRepository;

/**
 * Class InstallCommandTest
 *
 * @package tests\stages
 * @author jeyroik <jeyroik@gmail.com>
 */
class InstallCommandTest extends TestCase
{
    use TSnuffConsole;
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

    public function testInstall()
    {
        mkdir($this->basePath, 0777);
        
        /**
         * @var BufferedOutput $output
         */
        $output = $this->getOutput(true);
        $input = $this->getInput([
            InstallCommand::OPTION__PATH_SAVE => __DIR__ . '/../tmp_install',
            InstallCommand::OPTION__PATH_TEMPLATES => __DIR__ . '/../../resources',
            InstallCommand::OPTION__PATH_PACKAGES => __DIR__ . '/../resources',
            InstallCommand::OPTION__CFG_APP_FILENAME => 'extas.app.storage.test.json',
            InstallCommand::OPTION__CFG_PCKGS_FILENAME => 'extas.storage.test.json',
            InstallCommand::OPTION__ENTITY_APP_FILENAME => 'extas.app.test.json',
            InstallCommand::OPTION__ENTITY_PCKGS_FILENAME => 'extas.test.json'
        ]);

        $command = new InstallCommand();

        $command->run($input, $output);
        $outputText = $output->fetch();

        $this->assertStringContainsString('Extas Foundation v6 Installer', $outputText);
        $this->assertStringContainsString('Found app configurations + 1 package(s) configurations.', $outputText);
        $this->assertStringContainsString('Found app entities + 1 package(s) entities.', $outputText);
        $this->assertStringContainsString('Installation finished', $outputText);
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