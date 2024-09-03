<?php

namespace App\Rules;

use App\Models\Warehouse;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class WarehouseStoreValidName implements ValidationRule
{
    protected $companyId;

    public function __construct($companyId)
    {
        $this->companyId = $companyId;
    }

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $warehouse = Warehouse::whereCompanyId($this->companyId)->where('name', $value);

        if ($warehouse->exists()) {
            $fail('rules.unique_name')->translate();

            return;
        }
    }
}
