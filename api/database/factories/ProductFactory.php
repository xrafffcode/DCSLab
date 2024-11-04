<?php

namespace Database\Factories;

use App\Enums\ProductType;
use App\Enums\RecordStatus;
use App\Models\Brand;
use App\Models\ProductCategory;
use App\Models\ProductUnit;
use Illuminate\Database\Eloquent\Factories\Factory;
use Vinkla\Hashids\Facades\Hashids;

class ProductFactory extends Factory
{
    public function definition(): array
    {
        return [
            'code' => strtoupper(fake()->lexify()).fake()->numerify(),
            'name' => fake()->randomElement(['Minyak Goreng Sania 1L', 'Rokok Sampoerna Mild 16', 'Cokelat 100gr']),
            'product_type' => fake()->randomElement(ProductType::toArrayEnum()),
            'taxable_supply' => fake()->boolean(),
            'standard_rated_supply' => fake()->numberBetween(0, 100),
            'price_include_vat' => fake()->boolean(),
            'point' => fake()->numberBetween(0, 100),
            'use_serial_number' => fake()->boolean(),
            'has_expiry_date' => fake()->boolean(),
            'status' => fake()->randomElement(RecordStatus::toArrayEnum()),
            'remarks' => fake()->sentence(),
        ];
    }

    public function withRelation(bool $encode)
    {
        return $this->state(function (array $attributes) use ($encode) {
            $productCategory = ProductCategory::inRandomOrder()->first();
            $brand = Brand::inRandomOrder()->first();

            return [
                'product_category_id' => $encode ? Hashids::encode($productCategory->id) : $productCategory->id,
                'brand_id' => $encode ? Hashids::encode($brand->id) : $brand->id,
                'product_units' => ProductUnit::factory()->count(mt_rand(1, 3))->make()->toArray(),
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
        $text = fake()->randomElement(['Minyak Goreng Sania 1L', 'Rokok Sampoerna Mild 16', 'Cokelat 100gr']);

        return substr_replace($text, $str, random_int(0, strlen($text) - 1), 0);
    }

    public function setProductTypeAsProduct(?ProductType $productType = null)
    {
        return $this->state(function (array $attributes) {
            return [
                'type' => fake()->randomElement([
                    ProductType::RAW_MATERIAL->value,
                    ProductType::WORK_IN_PROGRESS->value,
                    ProductType::FINISHED_GOODS->value,
                ]),
            ];
        });
    }

    public function setProductTypeAsService()
    {
        return $this->state(function (array $attributes) {
            return [
                'type' => ProductType::SERVICE->value,
            ];
        });
    }
}
