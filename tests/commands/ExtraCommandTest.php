<?php
namespace tests\commands;

use extas\components\commands\ExtraCommand;
use extas\components\console\TSnuffConsole;
use extas\components\plugins\Plugin;
use extas\components\repositories\TSnuffRepository;
use extas\components\SystemContainer;
use extas\interfaces\stages\IStageExtraConfigure;
use extas\interfaces\stages\IStageExtraExecute;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Console\Output\BufferedOutput;

/**
 * Class ExtraCommandTest
 *
 * @package tests\commands
 * @author jeyroik <jeyroik@gmail.com>
 */
class ExtraCommandTest extends TestCase
{
    use TSnuffConsole;
    use TSnuffRepository;

    protected function setUp(): void
    {
        parent::setUp();

        $this->buildBasicRepos();
        SystemContainer::refresh();
    }

    /**
     * Clean up
     */
    public function tearDown(): void
    {
        $this->dropDatabase();

        SystemContainer::refresh();
    }

    public function testInstall()
    {
        $plugins = SystemContainer::getItem('plugins');
        $plugins->create(new Plugin([
            Plugin::FIELD__CLASS => 'tests\\resources\\PluginExtraConfigure',
            Plugin::FIELD__STAGE => IStageExtraConfigure::NAME
        ]));
        $plugins->create(new Plugin([
            Plugin::FIELD__CLASS => 'tests\\resources\\PluginExtraExecute',
            Plugin::FIELD__STAGE => IStageExtraExecute::NAME
        ]));

        /**
         * @var BufferedOutput $output
         */
        $output = $this->getOutput(true);
        $input = $this->getInput([
            'test' => 'set-on-run'
        ]);

        $command = new ExtraCommand();

        $command->run($input, $output);
        $outputText = $output->fetch();

        $this->assertStringContainsString('Got test parameter with value = set-on-run', $outputText);

        /**
         * @var BufferedOutput $output
         */
        $output = $this->getOutput(true);
        $input = $this->getInput([]);

        $command->run($input, $output);
        $outputText = $output->fetch();

        $this->assertStringContainsString('Got test parameter with value = default-value', $outputText);
    }
}