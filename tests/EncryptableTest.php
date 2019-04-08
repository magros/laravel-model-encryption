<?php
namespace Magros\Encryptable\Tests;
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


    /**
    * @test
    */
    public function it_test_that_encrypt_model_commands_encrypt_existing_records()
    {
        TestUser::$enableEncryption = false;

        $user = $this->createUser();

        $this->artisan('encryptable:encryptModel', ['model' => TestUser::class]);
        $raw = DB::table('test_users')->select('*')->first();

        $this->assertEquals($raw->email, $user->encryptAttribute($user->email));
        $this->assertEquals($raw->name, $user->encryptAttribute($user->name));

        TestUser::$enableEncryption = true;
    }


    /**
    * @test
    */
    public function it_test_that_where_in_query_builder_is_working()
    {
        $email = 'example@email.com';
        $this->createUser('Jhon Doe', $email);

        $user = TestUser::where('email', $email)->first();

        $this->assertNotNull($user);

    }

    /**
    * @test
    */
    public function it_assert_that_where_does_not_retrieve_a_user_with_incorrect_email()
    {
        $this->createUser();

        $user = TestUser::where('email', 'non_existing@email.com')->first();

        $this->assertNull($user);
    }


    /**
     * @test
     */
    public function it_test_that_validation_rule_exists_when_record_exists_is_working()
    {
        $email = 'example@email.com';

        $this->createUser('Jhon Doe', $email);

        $validator = validator(compact('email'), ['email'=>'exists_encrypted:test_users,email']);

        $this->assertFalse($validator->fails());
    }

    /**
     * @test
     */
    public function it_test_that_validation_rule_exists_when_record_does_not_exists_is_working()
    {
        $this->createUser();

        $validator = validator(
            ['email'=>'non_existing@email.com'],
            ['email'=>'exists_encrypted:test_users,email']
        );

        $this->assertTrue($validator->fails());
    }


    /**
     * @test
     */
    public function it_test_that_validation_rule_unique_when_record_exists_is_working()
    {
        $email = 'example@email.com';

        $this->createUser('Jhon Doe', $email);

        $validator = validator(compact('email'), ['email'=>'unique_encrypted:test_users,email']);

        $this->assertTrue($validator->fails());
    }

    /**
     * @test
     */
    public function it_test_that_validation_rule_unique_when_record_does_not_exists_is_working()
    {
        $this->createUser();

        $validator = validator(
            ['email'=>'non_existing@email.com'],
            ['email'=>'unique_encrypted:test_users,email']
        );

        $this->assertFalse( $validator->fails() );
    }

    /**
     * @test
     */
    public function it_tests_that_empty_values_are_not_encrypted()
    {
        $user = $this->createUser(null,'example@email.com');
        $raw = DB::table('test_users')->select('*')->first();
        $this->assertEmpty($raw->name);
        $this->assertEmpty($user->name);
    }


    /**
    * @test
    */
    public function it_test_that_decrypt_command_is_working()
    {
        TestUser::$enableEncryption = false;

        $user = $this->createUser();

        $this->artisan('encryptable:encryptModel', ['model' => TestUser::class]);
        $this->artisan('encryptable:decryptModel', ['model' => TestUser::class]);
        $raw = DB::table('test_users')->select('*')->first();


        $this->assertEquals($user->email, $raw->email);
        $this->assertEquals(strtolower($user->name), $raw->name);

        TestUser::$enableEncryption = true;
    }

    /**
     * @test
    */
    public function it_test_that_encrypted_value_is_stored_in_lower_case()
    {
        $email = 'Jhon@DOE.com';
        $user = $this->createUser('Jhon Doe', $email);

        $this->assertEquals($user->email, strtolower($email));
    }

    /**
     * @test
     */
    public function it_test_that_where_query_is_working_with_non_lowercase_values()
    {
        $this->createUser();
        $this->assertNotNull(TestUser::where('email','JhOn@DoE.cOm')->first());
    }

    /**
     * @test
     */
    public function it_test_that_detaching_encrypted_models_is_working()
    {
        $user = $this->createUser();
        $network = factory(TestSocialNetwork::class)->create();

        $network->users()->attach($user->id);

        $this->assertNotEmpty($network->users()->get());

        $network->users()->detach();

        $this->assertEmpty($network->users()->get());
    }

    /**
     * @test
    */
    public function it_test_that_convert_to_camelcase_is_working()
    {
        $user = $this->createUser('Jhon Doe');

        $this->assertEquals($user->name, 'Jhon Doe');
        $this->assertEquals($user->toArray()['name'], 'Jhon Doe');
    }
}