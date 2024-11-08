<?php

namespace Homeful\Prospects\Database\Factories;

use Homeful\Prospects\Model\Prospect;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProspectFactory extends Factory
{
    protected $model = Prospect::class;

    public function definition()
    {
        return [
            'reference_code' => $this->faker->name(),
            'first_name' => $this->faker->name(),
            'middle_name' => $this->faker->name(),
            'last_name' => $this->faker->name(),
            'name_extension' => $this->faker->name(),
            'address' => $this->faker->address(),
            'birthdate' => $this->faker->date(),
            'email' => $this->faker->email(),
            'mobile' => $this->faker->phoneNumber(),
            'id_type' => $this->faker->word(),
            'id_number' => $this->faker->uuid(),
            'idImage' => null,
            'selfieImage' => null,
            'idMarkImage' => null,
        ];
    }
}
