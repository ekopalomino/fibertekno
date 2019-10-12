<?php

namespace Erp\Models;

use Erp\Traits\Uuid;
use Illuminate\Database\Eloquent\Model;

class ReturSale extends Model
{
    use Uuid;

    protected $fillable = [
        'sales_id',
        'warehouse_id',
        'created_by',
        'updated_by',
    ];

    public function Sales()
    {
        return $this->belongsTo(Sale::class,'sales_id');
    }

    public function Locations()
    {
        return $this->belongsTo(Warehouse::class,'warehouse_id');
    }

    public $incrementing = false;
}
