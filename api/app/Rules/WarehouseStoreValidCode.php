<?php

namespace App\Rules;

use App\Actions\Warehouse\WarehouseActions;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class WarehouseStoreValidCode implements ValidationRule
{
    protected $companyId;

    public function __construct($companyId)
    {
        $this->companyId = $companyId;
    }

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if ($value !== config('dcslab.KEYWORDS.AUTO')) {
            $warehouseActions = new WarehouseActions();

            if (! $warehouseActions->isUniqueCode($this->companyId, $value)) {
                $fail('rules.unique_code')->translate();

                return;
            }
        }
    }
}
