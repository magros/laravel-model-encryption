<?php
namespace Tests;
use Illuminate\Support\Facades\DB;

class EncryptableTest extends TestCase {

    /**
    * @test
    */
    public function it_test_if_encryption_decoding_is_working()
    {
        $name = 'Jhon';
        $email = 'foo@bar.com';
        $phoneNumber = '123456798';

        $user = $this->createUserWithPhone($name, $email, $phoneNumber);
        $phone = $user->phones->first();

        $this->assertEquals($user->email, $email);
        $this->assertEquals($user->name, $name);

        $this->assertEquals($phone->phone_number, $phoneNumber);

    }

    /**
     * @test
     */
    public function it_test_if_encryption_encoding_is_working()
    {
        $name = 'Jhon';
        $email = 'foo@bar.com';
        $phoneNumber = '123456798';
        $user = $this->createUserWithPhone($name, $email, $phoneNumber);
        $phone = $user->phones->first();

        $userRaw = DB::table('test_users')->select('*')->first();
        $phoneRaw = DB::table('test_phones')->select('*')->first();

        $this->assertEquals($userRaw->email, $user->encryptAttribute($email));
        $this->assertEquals($userRaw->name, $user->encryptAttribute($name));
        $this->assertEquals($phoneRaw->phone_number, $phone->encryptAttribute($phoneNumber));
    }

}