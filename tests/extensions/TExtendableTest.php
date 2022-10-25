<?php

use \PHPUnit\Framework\TestCase;
use \extas\components\extensions\Extension;
use \extas\components\extensions\TExtendable;
use Dotenv\Dotenv;
use extas\components\repositories\TSnuffRepository;

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

            public function testMe(){}

            protected function getSubjectForExtension(): string
            {
                return 'test';
            }
        };

        $repo = $subject->extensions();
        $repo->create(new Extension([
            Extension::FIELD__CLASS => Extension::class,
            Extension::FIELD__SUBJECT => 'test',
            Extension::FIELD__INTERFACE => '',
            Extension::FIELD__METHODS => ['getSomething']
        ]));

        $this->assertTrue($subject->hasMethod('getSomething'), 'Subject has no method `getSomething`');
        $this->assertTrue($subject->hasMethod('testMe'), 'Subject has no method `testMe`');
    }
}
