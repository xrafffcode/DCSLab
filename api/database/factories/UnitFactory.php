<?php

namespace Database\Factories;

use App\Enums\UnitType;
use Illuminate\Database\Eloquent\Factories\Factory;

class UnitFactory extends Factory
{
    public function definition(): array
    {
        return [
            'code' => strtoupper(fake()->lexify()).fake()->numerify(),
            'name' => fake()->randomElement(['PCS', 'SET', 'BTL']),
            'description' => fake()->sentence(),
            'type' => fake()->randomElement(UnitType::toArrayEnum()),
        ];
    }

    public function insertStringInName(string $str)
    {
        return $this->state(function (array $attributes) use ($str) {
            return [
                'name' => $this->craftName($str),
            ];
        });
    }

    private function craftName(string $str)
    {
        $text = fake()->randomElement(['PCS', 'SET', 'BTL']);

        return substr_replace($text, $str, random_int(0, strlen($text) - 1), 0);
    }
}
