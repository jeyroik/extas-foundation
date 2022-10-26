<?php

use \PHPUnit\Framework\TestCase;
use extas\components\THasClass;
use extas\interfaces\IHasClass;
use extas\components\plugins\Plugin;
use extas\components\Item;
use Dotenv\Dotenv;
use extas\components\SystemContainer;

/**
 * Class THasClassTest
 *
 * @author jeyroik <jeyroik@gmail.com>
 */
class THasClassTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        $env = Dotenv::create(getcwd() . '/tests/');
        $env->load();
    }

    public function testSetAndGetClass()
    {
        $item = new class {
            use THasClass;
        };

        $item->setClass(Plugin::class);
        $this->assertEquals(Plugin::class, $item->getClass());
    }

    public function testBuildClassWithParameters()
    {
        $item = new class([IHasClass::FIELD__CLASS => Plugin::class]) extends Item {
            use THasClass;

            protected function getSubjectForExtension(): string
            {
                return 'test.item';
            }
        };

        /**
         * @var $built Plugin
         */
        $built = $item->buildClassWithParameters([
            Plugin::FIELD__CLASS => 'NotExistingClass',
            Plugin::FIELD__STAGE => 'not.existing.stage'
        ]);

        $this->assertEquals(
            [
                Plugin::FIELD__CLASS => 'NotExistingClass',
                Plugin::FIELD__STAGE => 'not.existing.stage'
            ],
            $built->__toArray()
        );
    }
}
