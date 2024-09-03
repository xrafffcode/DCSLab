<?php

namespace App\Rules;

use App\Models\Warehouse;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class WarehouseUpdateValidName implements ValidationRule
{
    protected $companyId;

    protected $warehouse;

    public function __construct($companyId, $warehouse)
    {
        $this->companyId = $companyId;
        $this->warehouse = $warehouse;
    }

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $data = Warehouse::whereCompanyId($this->companyId)->where('name', $value);

        if ($data->exists() && $this->warehouse->name !== $value) {
            $fail('rules.unique_name')->translate();

            return;
        }
    }
}
