<?php
namespace tests\stages;

use extas\components\commands\EnvCommand;
use extas\components\console\TSnuffConsole;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Console\Output\BufferedOutput;
use tests\resources\TBuildRepository;

/**
 * Class EnvCommandTest
 *
 * @package tests\stages
 * @author jeyroik <jeyroik@gmail.com>
 */
class EnvCommandTest extends TestCase
{
    use TSnuffConsole;

    protected string $basePath = '';

    protected function setUp(): void
    {
        parent::setUp();
    }

    /**
     * Clean up
     */
    public function tearDown(): void
    {

    }

    public function testEnv()
    {  
        /**
         * @var BufferedOutput $output
         */
        $output = $this->getOutput(true);
        $input = $this->getInput([]);

        $command = new EnvCommand();

        $command->run($input, $output);
        $outputText = $output->fetch();

        $this->assertStringContainsString('EXTAS__CONTAINER_PATH_STORAGE_LOCK', $outputText);
        $this->assertStringContainsString('EXTAS__PLUGINS_REPOSITORY', $outputText);
        $this->assertStringContainsString('EXTAS__EXTENSIONS_REPOSITORY', $outputText);
    }
}
