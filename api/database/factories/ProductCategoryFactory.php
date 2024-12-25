<?php

namespace Database\Factories;

use App\Enums\ProductCategoryType;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductCategoryFactory extends Factory
{
    public function definition(): array
    {
        $categories = [
            'Baju',
            'Celana',
            'Sepatu',
            'Topi',
            'Kacamata',
            'Jam',
            'Gelang',
            'Pensil',
            'Penghapus',
            'Buku',
            'Rautan',
            'Penggaris',
            'Kertas',
            'Spidol',
            'Tinta',
            'Pulpen',
            'Tas',
            'Celengan',
            'Baterai',
            'Kabel',
            'Kipas',
            'Lampu',
            'Kulkas',
            'Mesin Cuci',
            'TV',
            'Radio',
            'Kompor',
        ];

        return [
            'code' => strtoupper(fake()->lexify()).fake()->numerify(),
            'name' => fake()->randomElement($categories),
            'type' => fake()->randomElement(ProductCategoryType::toArrayEnum()),
        ];
    }

    public function forProduct()
    {
        return $this->state(function (array $attributes) {
            return [
                'type' => ProductCategoryType::PRODUCT,
            ];
        });
    }

    public function forService()
    {
        return $this->state(function (array $attributes) {
            return [
                'type' => ProductCategoryType::SERVICE,
            ];
        });
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
