<?php

define('EXTAS__BASE_PATH', getcwd());
define('EXTAS__CONTAINER_PATH_STORAGE_LOCK', getcwd() . '/resources/container.php.dist');
define('EXTAS__CONTAINER_PATH_STORAGE', getcwd() . '/resources/container.json.dist');

use \PHPUnit\Framework\TestCase;

/**
 * Class ItemTest
 * @author jeyroik <jeyroik@gmail.com>
 */
class ItemTest extends TestCase
{
    /**
     * Test default item configuration.
     */
    public function testConfigProperties(): void
    {
        $must = [
            'name' => 'child',
            'type' => 'test'
        ];

        $child = new class($must) extends \extas\components\Item {
            protected function getSubjectForExtension(): string
            {
                return 'test.child';
            }
        };

        $this->assertEquals('child', $child['name']);
        $this->assertEquals('child', $child->name);


        foreach ($child as $prop => $value) {
            if (isset($must[$prop])) {
                $this->assertEquals($must[$prop], $value);
            }
        }

        $this->assertEquals($must, $child->__toArray());
    }
}
