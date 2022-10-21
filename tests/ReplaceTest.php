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
        $this->markTestSkipped('This test is not updated to the Foundation v6');
        
        $template = 'Who are you, mr. @{user_2}? I am not @user, but @{user.3} can help you, @{user-4}.';
        $must = 'Who are you, mr. Test? I am not Broken, but someone can help you, mr. Unknown.';

        $this->assertEquals(
            $must,
            Replace::please()->apply([
                'user' => 'Broken',
                'user_2' => 'Test',
                'user.3' => 'someone',
                'user-4' => 'mr. Unknown',
            ])->to($template)
        );
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
        $template = 'Who are you, mr. @user.name?';
        $template0 = 'I am @user.name!';
        $must = ['Who are you, mr. Test?', 'I am Test!'];

        $this->assertEquals($must, Replace::please()->apply([
            'user' => [
                'name' => 'Test'
            ]
        ])->to([$template, $template0]));
    }
}
