<?php

namespace Database\Factories;

use App\Models\Manager;
use Illuminate\Database\Eloquent\Factories\Factory;

class ManagerFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Manager::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $name = ['Transport', 'Logistic'];
        $company = ['Intergate Baltic', 'Intergate Deutschland', 'Intergate Logistic', 'Intergate Mediterranean',
                    'Intergate Polska', 'Intergate Shipping', 'Intergate Transport'
                    ];
        return [
            'name' => $this->faker->name(),
            'company' => collect($company)->random(),
            'phone' => $this->faker->e164PhoneNumber(),
            'email' => $this->faker->companyEmail(),
            'type' => collect($name)->random(),
            'status' => rand(0,1),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
