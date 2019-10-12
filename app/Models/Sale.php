<?php

namespace Erp\Models;

use Erp\Traits\Uuid;
use Illuminate\Database\Eloquent\Model;

class Sale extends Model
{
    use Uuid;

    protected $fillable = [
        'order_ref',
        'client_id',
        'client_code',
        'billing_address',
        'shipping_address',
        'delivery_date',
        'quantity',
        'tax',
        'total',
        'status_id',
        'created_by',
        'updated_by',
    ];

    public $incrementing = false;

    public function Customers()
    {
        return $this->belongsTo(Contact::class,'client_id');
    }

    public function Author()
    {
        return $this->belongsTo(User::class,'created_by');
    }

    public function Editor()
    {
        return $this->belongsTo(User::class,'updated_by');
    }

    public function Items()
    {
        return $this->hasMany(SaleItem::class,'sales_id');
    }

    public function Statuses()
    {
        return $this->belongsTo(Status::class,'status_id');
    }

    public function Invoices()
    {
        return $this->hasOne(Invoice::class,'sales_order');
    }

    public function Deliveries()
    {
        return $this->hasOne(Delivery::class,'sales_ref');
    }

    public function Products()
    {
        return $this->belongsTo(Product::class,'product_id');
    }
}
