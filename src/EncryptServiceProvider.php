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
                EncryptModel::class,
                DecryptModel::class
            ]);
        }

        $this->publishes([
            __DIR__ . '/config/encrypt.php' => config_path('encrypt.php'),
        ], 'config');


        $this->bootValidators();

    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(__DIR__ . '/config/encrypt.php', 'encrypt');
    }


    private function bootValidators()
    {
        $encrypter = new Encrypter();

        $countRecords = function ($table, $column, $value) use ($encrypter) {
            $value = $encrypter->encrypt(strtolower($value));
            return DB::table($table)->where($column, $value)->count();
        };

        Validator::extend('exists_encrypted', function ($attribute, $value, $parameters, $validator) use($countRecords) {
            $table  = $parameters[0];
            $column = $parameters[1];

            return $countRecords($table,$column,$value) > 0;
        });

        Validator::extend('unique_encrypted', function ($attribute, $value, $parameters, $validator) use($countRecords) {
            $table  = $parameters[0];
            $column = $parameters[1];
            return $countRecords($table,$column,$value) == 0;
        });
    }
}