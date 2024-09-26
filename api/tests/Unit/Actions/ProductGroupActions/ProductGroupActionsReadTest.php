<?php

namespace Tests\Unit\Actions\ProductGroupActions;

use App\Actions\ProductGroup\ProductGroupActions;
use App\Enums\UserRoles;
use App\Models\Company;
use App\Models\ProductGroup;
use App\Models\Role;
use App\Models\User;
use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\Sequence;
use Tests\ActionsTestCase;

class ProductGroupActionsReadTest extends ActionsTestCase
{
    private ProductGroupActions $productGroupActions;

    protected function setUp(): void
    {
        parent::setUp();

        $this->productGroupActions = new ProductGroupActions();
    }

    public function test_product_group_actions_call_read_any_with_paginate_true_expect_paginator_object()
    {
        $user = User::factory()
            ->has(Company::factory()->setStatusActive()->setIsDefault()
                ->has(ProductGroup::factory())
            )->create();

        $company = $user->companies()->inRandomOrder()->first();

        $result = $this->productGroupActions->readAny(
            companyId: $company->id,
            search: '',
            paginate: true,
            page: 1,
            perPage: 10
        );

        $this->assertInstanceOf(Paginator::class, $result);
    }

    public function test_product_group_actions_call_read_any_with_paginate_false_expect_collection_object()
    {
        $user = User::factory()
            ->has(Company::factory()->setStatusActive()->setIsDefault()
                ->has(ProductGroup::factory())
            )->create();

        $company = $user->companies()->inRandomOrder()->first();

        $result = $this->productGroupActions->readAny(
            companyId: $company->id,
            search: '',
            paginate: false
        );

        $this->assertInstanceOf(Collection::class, $result);
    }

    public function test_product_group_actions_call_read_any_with_nonexistance_companyId_expect_empty_collection()
    {
        $maxId = Company::max('id') + 1;
        $result = $this->productGroupActions->readAny(
            companyId: $maxId,
            search: '',
            paginate: false
        );

        $this->assertInstanceOf(Collection::class, $result);
        $this->assertEmpty($result);
    }

    public function test_product_group_actions_call_read_any_with_search_parameter_expect_results()
    {
        $productGroupCount = 4;
        $idxTest = random_int(0, $productGroupCount - 1);
        $defaultName = ProductGroup::factory()->make()->name;
        $testname = ProductGroup::factory()->insertStringInName('testing')->make()->name;

        $user = User::factory()
            ->has(Company::factory()->setStatusActive()->setIsDefault()
                ->has(ProductGroup::factory()->count($productGroupCount)
                    ->state(new Sequence(
                        fn (Sequence $sequence) => [
                            'name' => $sequence->index == $idxTest ? $testname : $defaultName,
                        ]
                    ))
                )
            )
            ->create();

        $company = $user->companies()->inRandomOrder()->first();

        $result = $this->productGroupActions->readAny(
            companyId: $company->id,
            search: 'testing',
            paginate: true,
            page: 1,
            perPage: 10
        );

        $this->assertInstanceOf(Paginator::class, $result);
        $this->assertTrue($result->total() == 1);
    }

    public function test_product_group_actions_call_read_any_with_page_parameter_negative_expect_results()
    {
        $productGroupCount = 3;

        $user = User::factory()
            ->hasAttached(Role::where('name', '=', UserRoles::DEVELOPER->value)->first())
            ->has(Company::factory()->setStatusActive()
                ->has(ProductGroup::factory()->count($productGroupCount)))
            ->create();

        $company = $user->companies()->inRandomOrder()->first();

        $result = $this->productGroupActions->readAny(
            companyId: $company->id,
            search: '',
            paginate: true,
            page: -1,
            perPage: 10
        );

        $this->assertInstanceOf(Paginator::class, $result);
        $this->assertTrue($result->total() == 3);
    }

    public function test_product_group_actions_call_read_expect_object()
    {
        $user = User::factory()
            ->has(Company::factory()->setStatusActive()->setIsDefault()
                ->has(ProductGroup::factory())
            )->create();

        $productGroup = $user->companies()->inRandomOrder()->first()
            ->productGroups()->inRandomOrder()->first();

        $result = $this->productGroupActions->read($productGroup);

        $this->assertInstanceOf(ProductGroup::class, $result);
    }
}
