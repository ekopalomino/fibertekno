<?php

namespace iteos\Models;

use iteos\Traits\Uuid;
use Illuminate\Database\Eloquent\Model;

class Manufacture extends Model
{
    use Uuid;

    protected $fillable = [
        'sales_order',
        'order_ref',
        'product_name',
        'deadline',
        'status_id',
        'warehouse_id',
        'created_by',
        'approve_by',
        'process_by',
        'end_by',
        'start_production',
        'end_production',
        'man_plan',
        'man_result',
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

    public function Products()
    {
        return $this->belongsTo(Product::class,'product_id');
    }

    public function Child()
    {
        return $this->hasMany(ManufactureItem::class,'manufacture_id');
    }

    public function Locations()
    {
        return $this->belongsTo(Warehouse::class,'warehouse_id');
    }

    public function Uoms()
    {
        return $this->belongsTo(UomValue::class,'uom_id');
    }

    public function Statuses()
    {
        return $this->belongsTo(Status::class,'status_id');
    }
}
