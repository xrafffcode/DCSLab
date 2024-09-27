<?php

namespace Database\Factories;

use App\Enums\ProductCategoryEnum;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ProductGroup>
 */
class ProductGroupFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'code' => strtoupper(fake()->lexify()).fake()->numerify(),
            'name' => fake()->randomElement(['Buku', 'Elektronik', 'Tas']),
            'category' => fake()->randomElement(ProductCategoryEnum::toArrayEnum()),
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
        $text = fake()->randomElement(['Buku', 'Elektronik', 'Tas']);

        return substr_replace($text, $str, random_int(0, strlen($text) - 1), 0);
    }
}
