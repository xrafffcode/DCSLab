<?php

namespace App\Rules;

use App\Models\Branch;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class BranchStoreValidName implements ValidationRule
{
    protected $companyId;

    public function __construct($companyId)
    {
        $this->companyId = $companyId;
    }

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $branch = Branch::whereCompanyId($this->companyId)->where('name', $value);

        if ($branch->exists()) {
            $fail('rules.unique_name')->translate();

            return;
        }
    }
}
