<?php

namespace App\Rules;

use App\Actions\Branch\BranchActions;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class BranchUpdateValidCode implements ValidationRule
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
        if ($value !== config('dcslab.KEYWORDS.AUTO')) {
            $branchActions = new BranchActions();

            if (! $branchActions->isUniqueCode($this->companyId, $value, $this->branch->id)) {
                $fail('rules.unique_code')->translate();

                return;
            }
        }
    }
}
