<?php
namespace Magros\Encryptable;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
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

        $records = function ($table, $column, $value){
            $key = substr(hash('sha256', config('encrypt.key') ), 0, 16);
            $value = config('encrypt.prefix').'_'. openssl_encrypt($value, 'aes-128-ecb', $key, 0, $iv = '');
            return DB::table($table)->where($column, $value)->count();
        };

        Validator::extend('exists_encrypted', function ($attribute, $value, $parameters, $validator) use($records) {
            $table  = $parameters[0];
            $column = $parameters[1];

            return $records($table,$column,$value) > 0;
        });

        Validator::extend('unique_encrypted', function ($attribute, $value, $parameters, $validator) use($records) {
            $table  = $parameters[0];
            $column = $parameters[1];
            return $records($table,$column,$value) == 0;
        });

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