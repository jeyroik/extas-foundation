<?php

use \PHPUnit\Framework\TestCase;
use extas\components\extensions\ExtensionRepository;
use \extas\components\extensions\Extension;
use \extas\components\extensions\TExtendable;
use Dotenv\Dotenv;

/**
 * Class TExtendableTest
 *
 * @author jeyroik@gmail.com
 */
class TExtendableTest extends TestCase
{
    protected function setUp(): void
    {
        $this->markTestSkipped('This test is not updated to the Foundation v6');
        parent::setUp();
        $env = Dotenv::create(getcwd() . '/tests/');
        $env->load();
    }

    /**
     * Clean up
     */
    public function tearDown(): void
    {
        $repo = new ExtensionRepository();
        $repo->delete([Extension::FIELD__SUBJECT => 'test']);
    }

    public function testDecoration()
    {
        $subject = new class {
            use TExtendable;

            public function testMe(){}

            protected function getSubjectForExtension(): string
            {
                return 'test';
            }
        };

        $repo = new ExtensionRepository();
        $repo->create(new Extension([
            Extension::FIELD__CLASS => Extension::class,
            Extension::FIELD__SUBJECT => 'test',
            Extension::FIELD__INTERFACE => '',
            Extension::FIELD__METHODS => ['getClass']
        ]));

        $this->assertTrue($subject->hasMethod('getClass'), 'Subject has no method `getClass`');
        $this->assertTrue($subject->hasMethod('testMe'), 'Subject has no method `testMe`');
        $this->assertEquals(
            Extension::class,
            $subject->getClass(),
            'Incorrect extension class: ' . $subject->getClass()
        );
    }
}
