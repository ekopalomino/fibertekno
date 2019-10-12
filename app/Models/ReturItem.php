<?php

namespace Erp\Models;

use Illuminate\Database\Eloquent\Model;

class ReturItem extends Model
{
    protected $fillable = [
        'retur_id',
        'product_id',
        'quantity',
    ];

    public function Products()
    {
        return $this->belongsTo(Product::class,'product_id');
    }
}
