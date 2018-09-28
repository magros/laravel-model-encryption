<?php
namespace Magros\Encryptable;

use Illuminate\Support\ServiceProvider;

class EncryptServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * This method is called after all other service providers have
     * been registered, meaning you have access to all other services
     * that have been registered by the framework.
     *
     * @return void
     */
    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                EncryptModel::class
            ]);
        }

        $this->publishes([
            __DIR__ . '/config/encrypt.php' => config_path('encrypt.php'),
        ], 'config');
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(__DIR__.'/../config/encrypt.php', 'encrypt');
    }

//    /**
//     * Helper to get the config values.
//     *
//     * @param string $key
//     * @param null $default
//     * @return string
//     */
//    protected function config($key, $default = null)
//    {
//        return config("encrypt.$key", $default);
//    }
}