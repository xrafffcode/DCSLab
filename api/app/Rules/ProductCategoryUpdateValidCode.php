<?php

namespace App\Rules;

use App\Actions\ProductCategory\ProductCategoryActions;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class ProductCategoryUpdateValidCode implements ValidationRule
{
    protected $companyId;

    protected $productCategory;

    public function __construct($companyId, $productCategory)
    {
        $this->companyId = $companyId;
        $this->productCategory = $productCategory;
    }

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if ($value !== config('dcslab.KEYWORDS.AUTO')) {
            $productCategoryActions = new ProductCategoryActions();

            if (! $productCategoryActions->isUniqueCode($this->companyId, $value, $this->productCategory->id)) {
                $fail('rules.unique_code')->translate();

                return;
            }
        }
    }
}
