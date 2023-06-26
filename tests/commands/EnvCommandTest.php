<?php
namespace tests\commands;

use extas\components\commands\EnvCommand;
use extas\components\console\TSnuffConsole;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Console\Output\BufferedOutput;

/**
 * Class EnvCommandTest
 *
 * @package tests\commands
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
        $this->assertStringContainsString('EXTAS__APP', $outputText);
    }

    public function testGenerateDefault()
    {
        /**
         * @var BufferedOutput $output
         */
        $output = $this->getOutput(true);
        $input = $this->getInput([
            EnvCommand::OPTION__GENERATE => EnvCommand::FLAG__DEFAULT_VALUES,
            EnvCommand::OPTION__GENERATE_PATH => 'tests/tmp',
            EnvCommand::OPTION__CFG_APP_FILENAME => 'extas.app.storage.json',
            EnvCommand::OPTION__CFG_PCKGS_FILENAME => 'extas.storage.json',
        ]);

        $command = new EnvCommand();

        $command->run($input, $output);
        $outputText = $output->fetch();

        $this->assertStringContainsString('Begin .env generating...', $outputText);
        $this->assertStringContainsString('Generation done. Check tests/tmp/.env', $outputText);
        $this->assertStringContainsString('Adding envs from extas/foundation...', $outputText);

        $this->assertFileExists(__DIR__ . '/../tmp/.env');
        $this->assertFileEquals(__DIR__ . '/../resources/.env.default', __DIR__ . '/../tmp/.env');
    }

    public function testGenerateClean()
    {
        /**
         * @var BufferedOutput $output
         */
        $output = $this->getOutput(true);
        $input = $this->getInput([
            EnvCommand::OPTION__GENERATE => EnvCommand::FLAG__CLEAN,
            EnvCommand::OPTION__GENERATE_PATH => 'tests/tmp',
            EnvCommand::OPTION__CFG_APP_FILENAME => 'extas.app.storage.json',
            EnvCommand::OPTION__CFG_PCKGS_FILENAME => 'extas.storage.json',
        ]);

        $command = new EnvCommand();

        $command->run($input, $output);
        $outputText = $output->fetch();

        $this->assertStringContainsString('Begin .env generating...', $outputText);
        $this->assertStringContainsString('Generation done. Check tests/tmp/.env', $outputText);
        $this->assertStringContainsString('Adding envs from extas/foundation...', $outputText);

        $this->assertFileExists(__DIR__ . '/../tmp/.env');
        $this->assertFileEquals(__DIR__ . '/../resources/.env.clean', __DIR__ . '/../tmp/.env');
    }
}
