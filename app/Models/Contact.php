<?php

namespace iteos\Models;

use iteos\Traits\Uuid;
use Illuminate\Database\Eloquent\Model;

class Contact extends Model
{
    use Uuid;

    protected $fillable = [
        'ref_id',
        'type_id',
        'name',
        'company',
        'phone',
        'mobile',
        'email',
        'billing_address',
        'shipping_address',
        'payment_method',
        'payment_terms',
        'bank_name',
        'account_no',
        'tax',
        'tax_no',
        'created_by',
        'updated_by',
        'active',
    ];

    public $incrementing = false;

    public function Author()
    {
        return $this->belongsTo(User::class,'created_by');
    }

    public function Editor()
    {
        return $this->belongsTo(User::class,'updated_by');
    }

    public function Methods()
    {
        return $this->belongsTo(PaymentMethod::class,'payment_method');
    }

    public function Terms()
    {
        return $this->belongsTo(PaymentTerm::class,'payment_terms');
    }

    public function Statuses()
    {
        return $this->belongsTo(Status::class,'active');
    }
}
