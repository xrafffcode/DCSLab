<?php

namespace App\Models;

use App\Traits\BootableModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Customer extends Model
{
    use HasFactory;
    use SoftDeletes;
    use BootableModel;

    protected $fillable = [
        'company_id',
        'code',
        'name',
        'is_member',
        'customer_group_id',
        'zone',
        'max_open_invoice',
        'max_outstanding_invoice',
        'max_invoice_age',
        'payment_term_type',
        'payment_term',
        'taxable_enterprise',
        'tax_id',
        'remarks',
        'status',
        'user_id',
    ];

    protected $casts = [
        'status' => RecordStatus::class,
    ];

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function customerGroup()
    {
        return $this->belongsTo(CustomerGroup::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function customerAddresses()
    {
        return $this->hasMany(CustomerAddress::class);
    }
}
