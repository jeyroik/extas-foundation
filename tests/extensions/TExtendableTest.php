<?php

use \PHPUnit\Framework\TestCase;
use \extas\components\extensions\Extension;
use \extas\components\extensions\TExtendable;
use Dotenv\Dotenv;
use extas\components\repositories\TSnuffRepository;
use tests\resources\ExtensionCheckMethod;

/**
 * Class TExtendableTest
 *
 * @author jeyroik@gmail.com
 */
class TExtendableTest extends TestCase
{
    use TSnuffRepository;

    protected function setUp(): void
    {
        parent::setUp();
        $env = Dotenv::create(getcwd() . '/tests/');
        $env->load();
        $this->buildBasicRepos();
    }

    /**
     * Clean up
     */
    public function tearDown(): void
    {
        $this->unregisterSnuffRepos();
    }

    public function testDecoration()
    {
        $subject = new class {
            use TExtendable;

            public $name = 'test-decoration';

            public function testMe(){}

            protected function getSubjectForExtension(): string
            {
                return 'test';
            }
        };

        $repo = $subject->extensions();
        $repo->create(new Extension([
            Extension::FIELD__CLASS => ExtensionCheckMethod::class,
            Extension::FIELD__SUBJECT => 'test',
            Extension::FIELD__INTERFACE => '',
            Extension::FIELD__METHODS => ['getSomething']
        ]));

        $this->assertTrue($subject->hasMethod('getSomething'), 'Subject has no method `getSomething`');
        $this->assertTrue($subject->hasMethod('testMe'), 'Subject has no method `testMe`');
        $this->assertFalse($subject->hasMethod('unknown'), 'Subject has unknown method');

        $this->assertEquals(
            'test-decoration',
            $subject->getSomething(), 'Subject::getSomething() not worked. Check extension.'
        );

        $this->expectExceptionMessageMatches('/Missed or unknown method/');
        $subject->unknown();
    }
}
