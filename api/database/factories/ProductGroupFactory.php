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
            'name' => 'Kelompok Produk'.fake()->randomElement(['Buku', 'Elektronik', 'Tas']),
            'category' => fake()->randomElement(ProductCategoryEnum::toArrayEnum()),
        ];
    }
}
