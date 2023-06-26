<?php
namespace tests\commands;

use extas\components\commands\GenerateCommand;
use extas\components\console\TSnuffConsole;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Console\Output\BufferedOutput;
use Symfony\Component\Finder\Finder;

/**
 * Class GenerateCommandTest
 *
 * @package tests\commands
 * @author jeyroik <jeyroik@gmail.com>
 */
class GenerateCommandTest extends TestCase
{
    use TSnuffConsole;

    /**
     * Clean up
     */
    public function tearDown(): void
    {
        $finder = new Finder();
        $finder->name('*.json');

        foreach ($finder->in(__DIR__ . '/../tmp')->files() as $file) {
            unlink($file->getRealPath());
        }

        foreach ($finder->in(__DIR__ . '/../tmp')->directories() as $dir) {
            rmdir($dir->getRealPath());
        }
    }

    public function testGenerate()
    {  
        /**
         * @var BufferedOutput $output
         */
        $output = $this->getOutput(true);
        $input = $this->getInput([
            GenerateCommand::OPTION__GENERATE_PATH => __DIR__ . '/../tmp',
            GenerateCommand::OPTION__GENERATE_PATTERN => 'test_extas*'
        ]);

        $command = new GenerateCommand();

        $command->run($input, $output);
        $outputText = $output->fetch();
        $basePath = __DIR__ . '/../tmp/resources/test_extas.';

        $this->assertStringContainsString('Generation done', $outputText);
        $this->assertFileExists($basePath . 'json');
        $this->assertFileExists($basePath . 'app.json');
        $this->assertFileExists($basePath . 'storage.json');
        $this->assertFileExists($basePath . 'app.storage.json');

        $files = [
            $basePath . 'json',
            $basePath . 'app.json',
            $basePath . 'storage.json',
            $basePath . 'app.storage.json'
        ];

        foreach ($files as $name) {
            // check that php is worked
            $this->assertStringContainsString(10, file_get_contents($name));
        }
    }
}
