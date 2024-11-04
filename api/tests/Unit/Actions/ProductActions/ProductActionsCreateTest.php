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

class ProductActionsCreateTest extends ActionsTestCase
{
    private ProductActions $productActions;

    protected function setUp(): void
    {
        parent::setUp();

        $this->productActions = new ProductActions();
    }

    public function test_product_actions_call_create_expect_db_has_record()
    {
        $user = User::factory()
            ->has(Company::factory()->setStatusActive()->setIsDefault()
                ->has(ProductCategory::factory()->count(3))
                ->has(Brand::factory()->count(3)))
            ->create();

        $company = $user->companies()->inRandomOrder()->first();

        $productCategory = $company->productCategories()->inRandomOrder()->first();
        $brand = $company->brands()->inRandomOrder()->first();

        $productArr = Product::factory()
            ->for($company)
            ->for($productCategory)
            ->for($brand)
            ->make()->toArray();

        $result = $this->productActions->create($productArr);

        $this->assertDatabaseHas('products', [
            'id' => $result->id,
            'company_id' => $productArr['company_id'],
            'product_category_id' => $productArr['product_category_id'],
            'brand_id' => $productArr['brand_id'],
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

    public function test_product_actions_call_create_with_empty_array_parameters_expect_exception()
    {
        $this->expectException(Exception::class);
        $this->productActions->create([]);
    }
}
