<?php

namespace App\Models;

use App\Enums\ProductType;
use App\Enums\RecordStatus;
use App\Traits\BootableModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use BootableModel;
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'company_id',
        'product_category_id',
        'brand_id',
        'code',
        'name',
        'product_type',
        'taxable_supply',
        'standard_rated_supply',
        'price_include_vat',
        'point',
        'use_serial_number',
        'has_expiry_date',
        'type',
        'remarks',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'taxable_supply' => 'boolean',
            'price_include_vat' => 'boolean',
            'use_serial_number' => 'boolean',
            'has_expiry_date' => 'boolean',
            'type' => ProductType::class,
            'status' => RecordStatus::class,
        ];
    }

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function productCategory()
    {
        return $this->belongsTo(ProductCategory::class);
    }

    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }

    public function productUnits()
    {
        return $this->hasMany(ProductUnit::class);
    }

    public function scopeSearch($query, string $search)
    {
        return $query->where('code', 'like', '%'.$search.'%')
            ->orWhere('name', 'like', '%'.$search.'%')
            ->orWhere('remarks', 'like', '%'.$search.'%');
    }
}
