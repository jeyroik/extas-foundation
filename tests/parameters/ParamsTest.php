<?php

use extas\components\parameters\ParametredCollection;
use extas\components\repositories\TSnuffRepository;
use extas\interfaces\parameters\IParam;
use extas\interfaces\parameters\IParametred;
use extas\interfaces\parameters\IParams;
use \PHPUnit\Framework\TestCase;

/**
 * Class ParamsTest
 * @author jeyroik <jeyroik@gmail.com>
 */
class ParamsTest extends TestCase
{
    use TSnuffRepository;

    protected function setUp(): void
    {
        putenv("EXTAS__CONTAINER_PATH_STORAGE_LOCK=resources/container.dist.json");
        $this->buildBasicRepos();
    }

    protected function tearDown(): void
    {
        $this->dropDatabase(__DIR__);
        $this->deleteRepo('plugins');
        $this->deleteRepo('extensions');
    }

    public function testParams()
    {
        $parametredCollection = new ParametredCollection([
            'p1' => [
                IParametred::FIELD__NAME => 'p1',
                IParametred::FIELD__PARAMS => [
                    'par1' => [
                        'name' => 'par1'
                    ]
                ]
            ]
        ]);

        $parametred = $parametredCollection->buildOne('p1');
        $this->assertInstanceOf(IParametred::class, $parametred);

        $parametreds = $parametredCollection->buildAll();
        $this->assertCount(1, $parametreds);

        $parametreds = $parametredCollection->buildAll(['p1']);
        $this->assertCount(1, $parametreds);

        $params = $parametred->buildParams();
        $this->assertInstanceOf(IParams::class, $params);

        $param = $params->buildOne('par1');
        $this->assertInstanceOf(IParam::class, $param);

        $this->assertEquals('par1', $param->getName());
    }
}
