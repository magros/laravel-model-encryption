<?php
namespace Tests;

class EncryptableTest extends TestCase {

    /**
    * @test
    */
    public function example_test()
    {
        $name = 'Jhon';
        $email = 'jhon@doe.com';
        $user = $this->createUser($name, $email);
        $this->assertEquals($user->email, $email);
        $this->assertEquals($user->name, $name);
    }
}