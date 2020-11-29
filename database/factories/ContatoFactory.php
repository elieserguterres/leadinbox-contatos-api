<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Contato;
use Faker\Generator as Faker;
use Illuminate\Support\Str;

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| This directory should contain each of the model factory definitions for
| your application. Factories provide a convenient way to generate new
| model instances for testing / seeding your application's database.
|
*/

$faker = \Faker\Factory::create('pt_BR');

$factory->define(Contato::class, function () use ($faker) {
    return [
        'nome' => $faker->name,
        'ramal' => $faker->randomNumber(4),
        'celular' => $faker->phoneNumber,
        'telefone' => $faker->phoneNumber,
        'email' => $faker->unique()->safeEmail,
    ];
});
