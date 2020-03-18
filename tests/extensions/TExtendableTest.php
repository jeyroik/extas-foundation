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

            protected function getSubjectForExtension(): string
            {
                return 'test';
            }
        };

        $repo = new ExtensionRepository();
        $extension = new Extension([
            Extension::FIELD__CLASS => Extension::class,
            Extension::FIELD__SUBJECT => 'test',
            Extension::FIELD__INTERFACE => '',
            Extension::FIELD__METHODS => ['getClass']
        ]);
        $repo->create($extension);

        $this->assertEquals(Extension::class, $subject->getClass());
    }
}
