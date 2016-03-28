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
    ];
});

$factory->defineAs(App\Models\Entity::class, 'property', function ($faker) {
    return [
        'name' => $faker->name,
        'location' => $faker->address,
        'category' => 'property',
    ];
});

$factory->define(App\Models\Schema::class, function ($faker) {
    return [
        'name' => implode(' ', $faker->words(3)),
        'link' => $faker->url,
        'description' => $faker->sentence(5),
    ];
});

$factory->define(App\Models\Event::class, function ($faker) {
    static $index = 1;
    return [
        'id' => implode('_', ['event', $index++]),
        'content' => [
            'point' => 5,
        ],
        'condition' => [
            'category' => 'person'
        ],
    ];
});
