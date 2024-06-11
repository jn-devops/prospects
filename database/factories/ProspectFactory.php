<?php

namespace Homeful\Prospects\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Homeful\Prospects\Model\Prospect;

class ProspectFactory extends Factory
{
    protected $model = Prospect::class;

    public function definition()
    {
        return [
            'reference_code' => $this->faker->name(),
            'name' => $this->faker->name(),
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
