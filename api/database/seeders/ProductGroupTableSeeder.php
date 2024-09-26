<?php

namespace Database\Seeders;

use App\Models\Company;
use App\Models\ProductGroup;
use Illuminate\Database\Seeder;

class ProductGroupTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run($productGroupPerCompanies = 3, $onlyThisCompanyId = 0)
    {
        if ($onlyThisCompanyId != 0) {
            $companies = Company::where('id', '=', $onlyThisCompanyId)->get();
        } else {
            $companies = Company::get();
        }

        foreach ($companies as $company) {
            for ($i = 0; $i < $productGroupPerCompanies; $i++) {
                $productGroupFactory = ProductGroup::factory()->for($company);
                $productGroupFactory->create();
            }
        }
    }
}
