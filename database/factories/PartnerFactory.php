<?php

namespace Database\Factories;

use App\Models\Partner;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class PartnerFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = \App\Models\Partner::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $name = ['Client', 'Carrier'];
        return [
            'manager_id' => $this->faker->randomDigit(),
            'name' => $this->faker->name(),
            'company' => $this->faker->company(),
            'origin' => $this->faker->country(),
            'phone' => $this->faker->e164PhoneNumber(),
            'email' => $this->faker->companyEmail(),
            'type' => collect($name)->random(),
            'status' => rand(0,1),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
