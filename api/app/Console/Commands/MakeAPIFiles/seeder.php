<?php

namespace Database\Seeders;

use App\Models\Company;
use App\Models\RepToPascalThis;
use Illuminate\Database\Seeder;

class RepToPascalThisTableSeeder extends Seeder
{
    public function run(): void
    {
        $companies = Company::get();

        foreach ($companies as $company) {
            for ($i = 0; $i < 5; $i++) {
                RepToPascalThis::factory()->for($company)->create();
            }
        }
    }
}
