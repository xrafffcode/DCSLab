<?php

namespace App\Rules;

use App\Actions\ProductCategory\ProductCategoryActions;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class ProductCategoryStoreValidCode implements ValidationRule
{
    protected $companyId;

    public function __construct($companyId)
    {
        $this->companyId = $companyId;
    }

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if ($value !== config('dcslab.KEYWORDS.AUTO')) {
            $productCategoryActions = new ProductCategoryActions();

            if (! $productCategoryActions->isUniqueCode($this->companyId, $value, null)) {
                $fail('rules.unique_code')->translate();

                return;
            }
        }
    }
}
