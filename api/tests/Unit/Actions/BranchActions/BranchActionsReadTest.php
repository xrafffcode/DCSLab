<?php

namespace Tests\Unit\Actions\BranchActions;

use App\Actions\Branch\BranchActions;
use App\Enums\UserRoles;
use App\Models\Branch;
use App\Models\Company;
use App\Models\Role;
use App\Models\User;
use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\Sequence;
use Tests\ActionsTestCase;

class BranchActionsReadTest extends ActionsTestCase
{
    private BranchActions $branchActions;

    protected function setUp(): void
    {
        parent::setUp();

        $this->branchActions = new BranchActions();
    }

    public function test_branch_actions_call_read_any_with_paginate_true_expect_paginator_object()
    {
        $user = User::factory()
            ->has(Company::factory()->setStatusActive()->setIsDefault()
                ->has(Branch::factory()->setStatusActive()->setIsMainBranch())
            )->create();

        $company = $user->companies()->inRandomOrder()->first();

        $result = $this->branchActions->readAny(
            companyId: $company->id,
            useCache: true,
            with: [],
            withTrashed: false,

            search: '',
            isMain: null,
            status: null,

            paginate: true,
            page: 1,
            perPage: 10
        );

        $this->assertInstanceOf(Paginator::class, $result);
    }

    public function test_branch_actions_call_read_any_with_paginate_false_expect_collection_object()
    {
        $user = User::factory()
            ->has(Company::factory()->setStatusActive()->setIsDefault()
                ->has(Branch::factory()->setStatusActive()->setIsMainBranch())
            )->create();

        $company = $user->companies()->inRandomOrder()->first();

        $result = $this->branchActions->readAny(
            companyId: $company->id,
            useCache: true,
            with: [],
            withTrashed: false,

            search: '',
            isMain: null,
            status: null,

            paginate: false
        );

        $this->assertInstanceOf(Collection::class, $result);
    }

    public function test_branch_actions_call_read_any_with_nonexistance_companyId_expect_empty_collection()
    {
        $maxId = Company::max('id') + 1;
        $result = $this->branchActions->readAny(
            companyId: $maxId,
            useCache: true,
            with: [],
            withTrashed: false,

            search: '',
            isMain: null,
            status: null,

            paginate: false
        );

        $this->assertInstanceOf(Collection::class, $result);
        $this->assertEmpty($result);
    }

    public function test_branch_actions_call_read_any_with_search_parameter_expect_filtered_results()
    {
        $branchCount = 4;
        $idxMainBranch = random_int(0, $branchCount - 1);
        $idxTest = random_int(0, $branchCount - 1);
        $defaultName = Branch::factory()->make()->name;
        $testName = Branch::factory()->insertStringInName('testing')->make()->name;

        $user = User::factory()
            ->has(Company::factory()->setStatusActive()->setIsDefault()
                ->has(Branch::factory()->setStatusActive()->count($branchCount)
                    ->state(new Sequence(
                        fn (Sequence $sequence) => [
                            'is_main' => $sequence->index == $idxMainBranch ? true : false,
                            'name' => $sequence->index == $idxTest ? $testName : $defaultName,
                        ]
                    ))
                )
            )
            ->create();

        $company = $user->companies()->inRandomOrder()->first();

        $result = $this->branchActions->readAny(
            companyId: $company->id,
            useCache: true,
            with: [],
            withTrashed: false,

            search: 'testing',
            isMain: null,
            status: null,

            paginate: true,
            page: 1,
            perPage: 10
        );

        $this->assertInstanceOf(Paginator::class, $result);
        $this->assertTrue($result->total() == 1);
    }

    public function test_branch_actions_call_read_any_with_page_parameter_negative_expect_results()
    {
        $branchCount = 3;
        $idxMainBranch = random_int(0, $branchCount - 1);

        $user = User::factory()
            ->hasAttached(Role::where('name', '=', UserRoles::DEVELOPER->value)->first())
            ->has(Company::factory()->setStatusActive()
                ->has(Branch::factory()->setStatusActive()->count($branchCount)
                    ->state(new Sequence(
                        fn (Sequence $sequence) => [
                            'is_main' => $sequence->index == $idxMainBranch ? true : false,
                        ]
                    ))
                ))
            ->create();

        $company = $user->companies()->inRandomOrder()->first();

        $result = $this->branchActions->readAny(
            companyId: $company->id,
            useCache: true,
            with: [],
            withTrashed: false,

            search: '',
            isMain: null,
            status: null,

            paginate: true,
            page: -1,
            perPage: 10
        );

        $this->assertInstanceOf(Paginator::class, $result);
        $this->assertTrue($result->total() == 3);
    }

    public function test_branch_actions_call_read_any_with_perpage_parameter_negative_expect_results()
    {
        $branchCount = 3;
        $idxMainBranch = random_int(0, $branchCount - 1);

        $user = User::factory()
            ->hasAttached(Role::where('name', '=', UserRoles::DEVELOPER->value)->first())
            ->has(Company::factory()->setStatusActive()
                ->has(Branch::factory()->setStatusActive()->count($branchCount)
                    ->state(new Sequence(
                        fn (Sequence $sequence) => [
                            'is_main' => $sequence->index == $idxMainBranch ? true : false,
                        ]
                    ))
                ))
            ->create();

        $company = $user->companies()->inRandomOrder()->first();

        $result = $this->branchActions->readAny(
            companyId: $company->id,
            useCache: true,
            with: [],
            withTrashed: false,

            search: '',
            isMain: null,
            status: null,

            paginate: true,
            page: 1,
            perPage: -10
        );

        $this->assertInstanceOf(Paginator::class, $result);
        $this->assertTrue($result->total() == 3);
    }

    public function test_branch_actions_call_read_expect_object()
    {
        $user = User::factory()
            ->has(Company::factory()->setStatusActive()->setIsDefault()
                ->has(Branch::factory()->setStatusActive()->setIsMainBranch())
            )->create();

        $branch = $user->companies()->inRandomOrder()->first()
            ->branches()->inRandomOrder()->first();

        $result = $this->branchActions->read($branch);

        $this->assertInstanceOf(Branch::class, $result);
    }
}
