<?php

namespace App\Rules;

use App\Actions\Product\ProductActions;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class ProductStoreValidCode implements ValidationRule
{
    protected $companyId;

    public function __construct($companyId)
    {
        $this->companyId = $companyId;
    }

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if ($value !== config('dcslab.KEYWORDS.AUTO')) {
            $productActions = new ProductActions();

            if (! $productActions->isUniqueCode($this->companyId, $value, null)) {
                $fail('rules.unique_code')->translate();

                return;
            }
        }
    }
}
