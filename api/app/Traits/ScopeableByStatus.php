<?php

namespace App\Traits;

use App\Enums\RecordStatus;
use Illuminate\Database\Eloquent\Builder;

trait ScopeableByStatus
{
    public function scopeWhereStatus(Builder $query, array|RecordStatus|null $status = null)
    {
        if ($status != null) {
            if (is_array($status)) {
                $query->whereIn('status', $status);
            } else {
                $query->where('status', '=', $status);
            }
        }
    }

    public function scopeWhereStatusActive(Builder $query)
    {
        $query->where('status', '=', RecordStatus::ACTIVE);
    }

    public function scopeWhereStatusInactive(Builder $query)
    {
        $query->where('status', '=', RecordStatus::INACTIVE);
    }
}
