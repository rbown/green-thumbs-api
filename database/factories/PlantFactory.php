<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class PlantFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition(): array
    {
        return [
            'id' => $this->faker->uuid(),
            'name' => sprintf('Plant %s', $this->faker->randomDigit()),
            'species' => $this->faker->randomElement([
                'Acacia',
                'Frankenia',
                'Musa',
                'Hibbertia',
                'Pultenaea',
                'Medicosma',
            ]),
            'watering_instructions' => $this->faker->randomElement([
                'Only water when soil is dry about 1"-2" under the surface, watering slow and deeply',
                'In the spring and fall, check the soil for moisture every 3–5 days.',
                'In the summer, check the soil for moisture every 2–3 days for small plants and every 3–5 days for larger plants and trees.'
            ]),
            'photo' => $this->faker->imageUrl
        ];
    }
}
