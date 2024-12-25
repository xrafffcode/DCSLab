<?php

namespace Tests\Unit\Actions\ProductActions;

use App\Actions\Product\ProductActions;
use App\Models\Brand;
use App\Models\Company;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\User;
use Exception;
use Tests\ActionsTestCase;

class ProductActionsEditTest extends ActionsTestCase
{
    private ProductActions $productActions;

    protected function setUp(): void
    {
        parent::setUp();

        $this->productActions = new ProductActions();
    }

    public function test_product_actions_call_update_expect_db_updated()
    {
        $user = User::factory()
            ->has(Company::factory()->setStatusActive()->setIsDefault()
                ->has(ProductCategory::factory()->count(3))
                ->has(Brand::factory()->count(3)))
            ->create();

        $company = $user->companies()->inRandomOrder()->first();

        $productCategory = $company->productCategories()->inRandomOrder()->first();

        $brand = $company->brands()->inRandomOrder()->first();

        $product = Product::factory()
            ->for($company)
            ->for($productCategory)
            ->for($brand);

        $product = $product->create();

        $productArr = $product->toArray();

        $result = $this->productActions->update($product, $productArr);

        $this->assertInstanceOf(Product::class, $result);
        $this->assertDatabaseHas('products', [
            'id' => $product->id,
            'product_category_id' => $productArr['product_category_id'],
            'brand_id' => $productArr['brand_id'],
            'company_id' => $product->company_id,
            'code' => $productArr['code'],
            'name' => $productArr['name'],
            'product_type' => $productArr['product_type'],
            'taxable_supply' => $productArr['taxable_supply'],
            'standard_rated_supply' => $productArr['standard_rated_supply'],
            'price_include_vat' => $productArr['price_include_vat'],
            'point' => $productArr['point'],
            'use_serial_number' => $productArr['use_serial_number'],
            'has_expiry_date' => $productArr['has_expiry_date'],
            'status' => $productArr['status'],
            'remarks' => $productArr['remarks'],
        ]);
    }

    public function test_product_actions_call_update_with_empty_array_parameters_expect_exception()
    {
        $this->expectException(Exception::class);

        $user = User::factory()
            ->has(Company::factory()->setStatusActive()->setIsDefault()
                ->has(Product::factory())
            )->create();

        $product = $user->companies()->inRandomOrder()->first()
            ->products()->inRandomOrder()->first();

        $productArr = [];

        $this->productActions->update($product, $productArr);
    }
}
