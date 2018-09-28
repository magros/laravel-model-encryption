<?php

namespace Tests;

use Exception;
use Magros\Encryptable\EncryptModel;

class Kernel extends \Illuminate\Foundation\Console\Kernel
{
    /**
     * The bootstrap classes for the application.
     *
     * @return void
     */
    protected $bootstrappers = [];

    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        EncryptModel::class
    ];

    /**
     * Report the exception to the exception handler.
     *
     * @param  \Exception  $e
     *
     * @throws \Exception
     *
     * @return void
     */
    protected function reportException(Exception $e)
    {
        throw $e;
    }
}
