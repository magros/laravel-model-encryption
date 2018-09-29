<?php
use Tests\TestUser as User;

$factory->define(User::class, function (Faker\Generator $faker) use ($factory){
    return [
        'email' => $faker->email,
        'name' => $faker->name,
        'password'=> bcrypt($faker->password)
    ];
});

