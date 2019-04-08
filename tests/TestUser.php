<?php

namespace Magros\Encryptable\Tests;

use Illuminate\Database\Eloquent\Model;
use Magros\Encryptable\Encryptable;

class TestUser extends Model
{
    use Encryptable;

    protected $fillable = ['email', 'name', 'password'];
    protected $encryptable = ['email', 'name'];
    protected $camelcase = ['name'];

    public function phones()
    {
        return $this->hasMany(TestPhone::class);
    }

}