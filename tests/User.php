<?php
namespace Tests;
use Illuminate\Database\Eloquent\Model;
use Magros\Encryptable\Encryptable;

class User extends Model{

    use Encryptable;

    protected $fillable = ['email', 'name', 'password'];
    protected $encryptable = ['email', 'name'];

}