<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use Illuminate\Support\Number;

trait ScopeableByCompany
{
    public function scopeWhereCompanyId(Builder $query, Collection | Array | Number | null $companyId = null)
    {
        if ($companyId != null) {
            if (is_a($companyId, 'Illuminate\Support\Collection')) {
                $query->whereIn('company_id', $companyId->toArray());
            } elseif (is_array($companyId)) {
                $query->whereIn('company_id', $companyId);
            } else {
                $query->where('company_id', '=', $companyId);
            }
        }
    }
}
