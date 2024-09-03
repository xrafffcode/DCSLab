<?php

namespace App\Rules;

use App\Actions\Warehouse\WarehouseActions;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class WarehouseUpdateValidCode implements ValidationRule
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
        if ($value !== config('dcslab.KEYWORDS.AUTO')) {
            $warehouseActions = new WarehouseActions();

            if (! $warehouseActions->isUniqueCode($this->companyId, $value, $this->warehouse->id)) {
                $fail('rules.unique_code')->translate();

                return;
            }
        }
    }
}
