<?php

namespace Homeful\Prospects\Database\Factories;

use Homeful\Prospects\Model\Prospect;
use Illuminate\Database\Eloquent\Factories\Factory;
use Faker\Factory as FakerFactory;

class ProspectFactory extends Factory
{
    protected $model = Prospect::class;

    public function definition()
    {
        // Create a Faker instance with the Filipino locale
        $faker = FakerFactory::create('en_PH');

        return [
            'reference_code' => 'JN-' . $faker->numerify('######'), // Generates a code like 'JN-123456'
            'first_name' => $faker->firstName(),
            'middle_name' => $faker->lastName(),
            'last_name' => $faker->lastName(),
            'name_extension' => $faker->optional()->suffix(),
            'address' => $faker->address(),
            'birthdate' => $faker->date(),
            'email' => $faker->unique()->safeEmail(),
            'mobile' => '+63' . $faker->numerify('9#########'), // Philippine mobile number
            'id_type' => $faker->randomElement(['TIN', 'SSS', 'GSIS', 'UMID']),
            'id_number' => $faker->unique()->numerify('##########'),
            'idImage' => null,
            'selfieImage' => null,
            'idMarkImage' => null,
        ];
    }
}
