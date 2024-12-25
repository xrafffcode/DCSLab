<?php

namespace App\Rules;

use App\Actions\Product\ProductActions;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class ProductUpdateValidCode implements ValidationRule
{
    protected $companyId;

    protected $product;

    public function __construct($companyId, $product)
    {
        $this->companyId = $companyId;
        $this->product = $product;
    }

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if ($value !== config('dcslab.KEYWORDS.AUTO')) {
            $productActions = new ProductActions();

            if (! $productActions->isUniqueCode($this->companyId, $value, $this->product->id)) {
                $fail('rules.unique_code')->translate();

                return;
            }
        }
    }
}
