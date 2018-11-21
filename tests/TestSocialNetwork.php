<?php

namespace Magros\Encryptable\Tests;

use Illuminate\Database\Eloquent\Model;

class TestSocialNetwork extends Model
{
    public function users()
    {
        return $this->belongsToMany(TestUser::class);
    }
}