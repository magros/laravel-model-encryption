<?php

namespace Magros\Encryptable\Tests;

use Illuminate\Database\Eloquent\Model;
use Magros\Encryptable\Encryptable;

class TestPhone extends Model
{
    use Encryptable;

    protected $fillable = ['phone_number'];
    protected $encryptable = ['phone_number'];

    public function user()
    {
        return $this->belongsTo(TestUser::class);
    }
}