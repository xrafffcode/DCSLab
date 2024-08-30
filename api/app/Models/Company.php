<?php

namespace App\Models;

use App\Enums\RecordStatus;
use App\Traits\BootableModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Company extends Model
{
    use BootableModel;
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'code',
        'name',
        'address',
        'default',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'default' => 'boolean',
            'status' => RecordStatus::class,
        ];
    }

    public function users()
    {
        return $this->belongsToMany(User::class);
    }

    public function branches()
    {
        return $this->hasMany(Branch::class);
    }

    public function warehouses()
    {
        return $this->hasMany(Warehouse::class);
    }

    public function scopeSearch($query, string $search)
    {
        return $query->where('code', 'like', '%'.$search.'%')
            ->orWhere('name', 'like', '%'.$search.'%')
            ->orWhere('address', 'like', '%'.$search.'%');
    }
}
