<?php
namespace tests\samples;

use extas\interfaces\samples\IHasSample;
use extas\interfaces\samples\parameters\ISampleParameter;
use extas\components\repositories\TSnuffRepository;
use extas\components\samples\Sample;
use extas\components\THasName;
use extas\components\Item;
use extas\components\samples\THasSample;
use extas\components\samples\parameters\SampleParameter;

use PHPUnit\Framework\TestCase;

/**
 * Class SampleTest
 * @skip
 * @author jeyroik@gmail.com
 */
class SampleTest extends TestCase
{
    use TSnuffRepository;

    protected function setUp(): void
    {
        $this->buildBasicRepos();
    }

    protected function tearDown(): void
    {
        $this->dropDatabase();
    }

    public function testParameters()
    {
        $sample = new Sample([
            Sample::FIELD__PARAMETERS => [
                'test1' => [
                    ISampleParameter::FIELD__NAME => 'test1',
                    ISampleParameter::FIELD__VALUE => 'test1-v'
                ],
                'test2' => [
                    ISampleParameter::FIELD__NAME => 'test2',
                    ISampleParameter::FIELD__VALUE => 'test2-v'
                ]
            ]
        ]);

        $this->assertEquals(
            [
                'test1' => [
                    ISampleParameter::FIELD__NAME => 'test1',
                    ISampleParameter::FIELD__VALUE => 'test1-v'
                ],
                'test2' => [
                    ISampleParameter::FIELD__NAME => 'test2',
                    ISampleParameter::FIELD__VALUE => 'test2-v'
                ]
            ],
            $sample->getParametersOptions()
        );

        $this->assertTrue($sample->hasParameter('test1'));
        $this->assertCount(2, $sample->getParameters());

        foreach ($sample->eachParameter() as $parameterName => $parameter) {
            $this->assertTrue(in_array($parameterName, ['test1', 'test2']));
            $this->assertTrue($parameter instanceof ISampleParameter);
        }

        $values = $sample->getParametersValues();
        foreach ($values as $name => $value) {
            $this->assertTrue(in_array($name, ['test1', 'test2']));
            $this->assertTrue(in_array($value, ['test1-v', 'test2-v']));
        }

        $this->assertEquals(['test1', 'test2'], $sample->getParametersNames());

        $param = $sample->getParameter('test1');
        $this->assertNotEmpty($param);
        $this->assertEquals(['test1', 'test1-v'], [$param->getName(), $param->getValue()]);

        $nullParam = $sample->getParameter('unknown');
        $this->assertEmpty($nullParam);

        $paramOptions = $sample->getParameterOptions('test1');
        $this->assertTrue(is_array($paramOptions));
        $this->assertEquals('test1', $paramOptions[ISampleParameter::FIELD__NAME]);

        $this->assertEquals('test1-v', $sample->getParameterValue('test1'));

        $params = $sample->getParameters();
        foreach ($params as $index => $param) {
            $param->setName($param->getName() . '*');
            $params[$index] = $param;
        }
        $sample->setParameters($params);
        $this->assertFalse($sample->hasParameter('test1'));
        $sample->setParametersOptions([
            [
                ISampleParameter::FIELD__NAME => 'test1',
                ISampleParameter::FIELD__VALUE => 'test1-v'
            ],
            [
                ISampleParameter::FIELD__NAME => 'test1*',
                ISampleParameter::FIELD__VALUE => '*'
            ]
        ]);
        $this->assertEquals(
            [
                'test1' => 'test1-v',
                'test1*' => '*'
            ],
            $sample->getParametersValues()
        );
        $sample->setParametersValues([
            'test1' => '1',
            'test1*' => '2'
        ]);
        $this->assertEquals(
            [
                'test1' => '1',
                'test1*' => '2'
            ],
            $sample->getParametersValues()
        );

        $sample->setParameter('test1', [
            ISampleParameter::FIELD__NAME => 'test1',
            ISampleParameter::FIELD__VALUE => 'test1-v'
        ]);
        $this->assertEquals('test1-v', $sample->getParameterValue('test1'));
        $sample->setParameterValue('test1', '*');
        $this->assertEquals('*', $sample->getParameterValue('test1'));

        $sample->updateParameter('test1', [ISampleParameter::FIELD__VALUE => 'test1-v']);
        $this->assertEquals('test1-v', $sample->getParameterValue('test1'));

        $sample->addParameters([
            new SampleParameter([
                SampleParameter::FIELD__NAME => 'test3',
                SampleParameter::FIELD__VALUE => 'test3-v'
            ])
        ]);

        $this->assertTrue($sample->hasParameter('test3'));

        $sample->addParameterByValue('test4', 'test4-v');
        $this->assertTrue($sample->hasParameter('test4'));

        $sample->addParametersByValues([
            'test5' => 'test5-v'
        ]);
        $this->assertTrue($sample->hasParameter('test5'));

        $sample->addParameterByOptions([
            SampleParameter::FIELD__NAME => 'test6',
            SampleParameter::FIELD__VALUE => 'test6-v'
        ]);
        $this->assertTrue($sample->hasParameter('test6'));

        $sample->addParametersByOptions([
            [
                SampleParameter::FIELD__NAME => 'test7',
                SampleParameter::FIELD__VALUE => 'test7-v'
            ]
        ]);
        $this->assertTrue($sample->hasParameter('test7'));

        $this->expectExceptionMessage('Unknown parameter "unknown"');
        $sample->getParameterOptions('unknown');
    }

    public function testAddNotASampleParameter()
    {
        $sample = new Sample();
        $this->expectExceptionMessage('Not an extas\\interfaces\\samples\\ISampleParameter');
        $sample->addParameters([
            [
                ISampleParameter::FIELD__NAME => 'not an object'
            ]
        ]);
    }

    public function testAddAlreadyExistedSampleParameter()
    {
        $sample = new Sample([
            Sample::FIELD__PARAMETERS => [
                'test' => [
                    ISampleParameter::FIELD__NAME => 'test'
                ]
            ]
        ]);
        $this->expectExceptionMessage('Parameter "test" already exists');
        $sample->addParameters([
            new SampleParameter([
                ISampleParameter::FIELD__NAME => 'test'
            ])
        ]);
    }

    public function testSetValueUnknownParameter()
    {
        $sample = new Sample();
        $this->expectExceptionMessage('Unknown parameter "unknown"');
        $sample->setParameterValue('unknown', 'test');
    }

    public function testHasSample()
    {
        $hasSample = new class () extends Item implements IHasSample {
            use THasSample;
            use THasName;

            protected function getSubjectForExtension(): string
            {
                return '';
            }
        };
        $hasSample->setSampleName('test');
        $this->assertEquals('test', $hasSample->getSampleName());

        $newSample = new Sample([Sample::FIELD__NAME => 'test2']);
        $hasSample->buildFromSample($newSample, 'test_name');
        $this->assertEquals('test2', $hasSample->getSampleName());
        $this->assertEquals('test_name', $hasSample->getName());
    }
}
