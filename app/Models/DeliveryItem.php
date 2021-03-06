<?php

namespace iteos\Models;

use Illuminate\Database\Eloquent\Model;

class DeliveryItem extends Model
{
    protected $fillable = [
        'delivery_id',
        'product_name',
        'quantity',
        'shipping',
        'uom_id',
    ];

    public function Parent()
    {
        return $this->belongsTo(Delivery::class,'delivery_id');
    }

    public function Uoms()
    {
        return $this->belongsTo(UomValue::class,'uom_id');
    }
}
