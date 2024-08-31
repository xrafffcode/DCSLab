<?php

namespace App\Rules;

use App\Models\Branch;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class BranchUpdateValidName implements ValidationRule
{
    protected $companyId;

    protected $branch;

    public function __construct($companyId, $branch)
    {
        $this->companyId = $companyId;
        $this->branch = $branch;
    }

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $data = Branch::whereCompanyId($this->companyId)->where('name', $value);

        if ($data->exists() && $this->branch->name !== $value) {
            $fail('rules.unique_name')->translate();

            return;
        }
    }
}
