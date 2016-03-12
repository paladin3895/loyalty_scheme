<?php

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| Here you may define all of your model factories. Model factories give
| you a convenient way to create models for testing and seeding your
| database. Just tell the factory how a default model should look.
|
*/

$factory->defineAs(App\Models\Entity::class, 'person', function ($faker) {
    return [
        'name' => $faker->name,
        'email' => $faker->email,
        'category' => 'person',
        'client_id' => null,
    ];
});

$factory->defineAs(App\Models\Entity::class, 'property', function ($faker) {
    return [
        'name' => $faker->name,
        'location' => $faker->address,
        'category' => 'property',
        'client_id' => null,
    ];
});

$factory->define(App\Models\Schema::class, function ($faker) {
    return [
        'name' => implode(' ', $faker->words(3)),
        'link' => $faker->url,
        'description' => $faker->sentence(5),
        'client_id' => null,
    ];
});

$factory->define(App\Models\Event::class, function ($faker) {
    return [
        'id' => implode('_', $faker->words(3)),
        'content' => json_encode([
            'point' => 5,
        ]),
        'condition' => json_encode([
            'category' => $faker->randomElement(['person', 'property', 'post'])
        ]),
        'client_id' => null,
    ];
});
