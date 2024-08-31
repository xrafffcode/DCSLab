<?php

namespace App\Rules;

use App\Enums\RecordStatus;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class CompanyStoreValidStatus implements ValidationRule
{
    private ?bool $isDefault;

    public function __construct(?bool $isDefault)
    {
        $this->isDefault = $isDefault;
    }

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if ($this->isDefault == true && $value == RecordStatus::INACTIVE->value) {
            $fail('rules.company.deactivate_default_company')->translate();

            return;
        }
    }
}
