<?php
namespace Tests;
use Illuminate\Contracts\Console\Kernel;
use Magros\Encryptable\EncryptServiceProvider;
use Orchestra\Testbench\TestCase as BaseTestCase;

class TestCase extends BaseTestCase
{
    /**
     * Load the package service provider.
     *
     * @param $app
     * @return array
     */
    protected function getPackageProviders($app)
    {
        return [EncryptServiceProvider::class];
    }

    /**
     * Define environment setup.
     *
     * @param  \Illuminate\Foundation\Application  $app
     * @return void
     */
    protected function getEnvironmentSetUp($app)
    {
        $app['config']->set('database.default', 'testbench');
        $app['config']->set('database.connections.testbench', [
            'driver'   => 'sqlite',
            'database' => ':memory:',
            'prefix'   => '',
        ]);
    }

    /**
     * Set up the test case.
     *
     */
    function setUp()
    {
        parent::setUp();
        $this->loadLaravelMigrations();
    }


//    /**
//     * Resolve application Console Kernel implementation.
//     *
//     * @param  \Illuminate\Foundation\Application  $app
//     * @return void
//     */
//    protected function resolveApplicationConsoleKernel($app)
//    {
//        $app->singleton('Illuminate\Contracts\Console\Kernel', \Tests\Kernel::class);
//    }

    /**
     * Create a test user.
     *
     * @param $name
     * @param $email
     * @return User
     */
    public function createUser($name, $email)
    {
        $password = bcrypt('test');

        return User::create(compact('name', 'email', 'password'));
    }

}