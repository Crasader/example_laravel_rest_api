<?php

use Faker\Generator as Faker;

$factory->define(App\Pdf::class, function (Faker $faker) {
    $userId = 0;
    $type = $faker->unique()->numberBetween(0, 2);
    $typeTitles = ['short', 'full', 'advanced'];
    $filename = sprintf('%s_%d.pdf', $typeTitles[$type], $userId);

    return [
        'user_id' => $userId,
        'type' => $type,
        'custom_text' => $faker->text,
        'filename' => $filename,
        'link' => $faker->unique()->url,
    ];
});
