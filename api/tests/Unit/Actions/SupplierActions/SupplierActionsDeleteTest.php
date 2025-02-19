<?php

namespace Tests\Unit\Actions\SupplierActions;

use App\Actions\Supplier\SupplierActions;
use App\Models\Company;
use App\Models\Supplier;
use App\Models\User;
use Tests\ActionsTestCase;

class SupplierActionsDeleteTest extends ActionsTestCase
{
    private SupplierActions $supplierActions;

    protected function setUp(): void
    {
        parent::setUp();

        $this->supplierActions = new SupplierActions();
    }

    public function test_supplier_actions_call_delete_expect_bool()
    {
        $user = User::factory()
            ->has(Company::factory()->setStatusActive()->setIsDefault()
                ->has(Supplier::factory())
            )->create();

        $supplier = $user->companies()->inRandomOrder()->first()
            ->suppliers()->inRandomOrder()->first();
        $result = $this->supplierActions->delete($supplier);

        $this->assertIsBool($result);
        $this->assertTrue($result);
        $this->assertSoftDeleted('suppliers', [
            'id' => $supplier->id,
        ]);
    }
}
