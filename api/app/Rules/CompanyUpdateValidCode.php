<?php

namespace App\Rules;

use App\Actions\Company\CompanyActions;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class CompanyUpdateValidCode implements ValidationRule
{
    protected $user;

    protected $company;

    public function __construct($user, $company)
    {
        $this->user = $user;
        $this->company = $company;
    }

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if ($value !== config('dcslab.KEYWORDS.AUTO')) {
            $companyActions = new CompanyActions();

            if (! $companyActions->isUniqueCode($this->user, $value, $this->company->id)) {
                $fail('rules.unique_code')->translate();

                return;
            }
        }
    }
}
