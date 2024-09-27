<?php

namespace Database\Seeders;

use App\Models\Company;
use App\Models\ProductGroup;
use Illuminate\Database\Seeder;

class ProductGroupTableSeeder extends Seeder
{
    public function run(?int $companyId, ?int $qtyPerCompany)
    {
        $query = Company::query();
        if ($companyId) $query->where('id', '=', $companyId);
        $companies = $query->get();

        if (! $qtyPerCompany) $qtyPerCompany = 5;
        foreach ($companies as $company) {
            for ($i = 0; $i < $qtyPerCompany; $i++) {
                $productGroupFactory = ProductGroup::factory()->for($company);
                $productGroupFactory->create();
            }
        }
    }
}
