<?php

use \PHPUnit\Framework\TestCase;
use \extas\components\Replace;

/**
 * Class ReplaceTest
 * @author jeyroik <jeyroik@gmail.com>
 */
class ReplaceTest extends TestCase
{
    public function testSingleOneLevelReplace()
    {
        $template = 'Who are you, mr. @user?';
        $must = 'Who are you, mr. Test?';

        $this->assertEquals($must, Replace::please()->apply(['user' => 'Test'])->to($template));
    }

    public function testSingleMultiLevelReplace()
    {
        $template = 'Who are you, mr. @user.name?';
        $must = 'Who are you, mr. Test?';

        $this->assertEquals($must, Replace::please()->apply([
            'user' => [
                'name' => 'Test'
            ]
        ])->to($template));
    }

    public function testMultiOneLevelReplace()
    {
        $template = 'Who are you, mr. @user?';
        $template0 = 'I am @user!';
        $must = ['Who are you, mr. Test?', 'I am Test!'];

        $this->assertEquals($must, Replace::please()->apply(['user' => 'Test'])->to([$template, $template0]));
    }

    public function testMultiMultiLevelReplace()
    {
        $template = 'Who are you, mr. @user?';
        $template0 = 'I am @user!';
        $must = ['Who are you, mr. Test?', 'I am Test!'];

        $this->assertEquals($must, Replace::please()->apply([
            'user' => [
                'name' => 'Test'
            ]
        ])->to([$template, $template0]));
    }
}
