<?php

namespace Database\Factories;

use App\Models\MarketingSearch;
use Illuminate\Database\Eloquent\Factories\Factory;

class MarketingSearchFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = MarketingSearch::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {

        $created_at = $this->faker->dateTimeBetween('-1 year', '+1 week');

        return [
            'name' => $this->faker->word(), 
            'country' => $this->faker->countryCode(),
            'email' => $this->faker->email(),
            'phone' => $this->faker->numerify('+###########'),
            'type' => $this->faker->randomElement(['Client', 'Carrier']),
            'creator' => 22,
            'created_at' => $created_at,
            'updated_at' => $created_at,
        ];
    }
}
