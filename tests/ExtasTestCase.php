<?php
namespace tests;

use extas\components\repositories\TSnuffRepository;
use \PHPUnit\Framework\TestCase;

class ExtasTestCase extends TestCase
{
    use TSnuffRepository;

    protected array $libsToInstall = [
        //'vendor/lib' => ['php', 'json'] storage ext, extas ext
    ];
    protected array $installedTables = [];
    protected bool $isNeedInstallLibsItems = false;
    protected string $testPath = '';

    protected function setUp(): void
    {
        putenv("EXTAS__CONTAINER_PATH_STORAGE_LOCK=vendor/jeyroik/extas-foundation/resources/container.dist.json");
        $this->buildBasicRepos();

        foreach($this->libsToInstall as $name => $exts) {
            list($storageExt) = $exts;
            $installedTables = $this->buildLibsRepos($name, $this->testPath, $storageExt);
            $this->installedTables = array_merge($this->installedTables, $installedTables);

            if ($this->isNeedInstallLibsItems) {
                list(,$extasExt) = $exts;
                $this->installLibItems($name, $this->testPath, $extasExt);
            }
        }
    }

    protected function tearDown(): void
    {
        foreach ($this->installedTables as $name) {
            $this->deleteRepo($name);
        }
        $this->deleteRepo('plugins');
        $this->deleteRepo('extensions');
        $this->dropDatabase(__DIR__);
    }
}
