<?php

use extas\components\Item;
use extas\components\parameters\Param;
use extas\components\parameters\ParametredCollection;
use extas\components\parameters\THasParams;
use extas\components\repositories\TSnuffRepository;
use extas\interfaces\parameters\IHaveParams;
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

    public function testHasParams()
    {
        $withParams = new class ([
            IHaveParams::FIELD__PARAMS => [
                'test' => [
                    IParam::FIELD__NAME => 'test',
                    IParam::FIELD__TITLE => 'test_title',
                    IParam::FIELD__DESCRIPTION => 'test_description',
                    IParam::FIELD__VALUE => 'test_value'
                ]
            ]
        ]) extends Item implements IHaveParams {
            use THasParams;

            protected function getSubjectForExtension(): string
            {
                return 'test';
            }
        };

        $this->assertCount(1, $withParams->getParams());
        $withParams->addParam(new Param([
            Param::FIELD__NAME => 'test2',
            Param::FIELD__VALUE => 'test_value_2'
        ]));

        $this->assertCount(2, $withParams->getParams());
        $this->assertEquals([
            'test' => 'test_value',
            'test2' => 'test_value_2'
        ], $withParams->getParamsValues());

        $withParams->setParams([
            'test3' => [
                IParam::FIELD__NAME => 'test3',
                IParam::FIELD__TITLE => 'test3_title',
                IParam::FIELD__DESCRIPTION => 'test3_description',
                IParam::FIELD__VALUE => 'test_value_3'
            ]
        ]);

        $this->assertCount(1, $withParams->getParams());
    }
}
