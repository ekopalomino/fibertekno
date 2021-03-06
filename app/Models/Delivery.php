<?php

namespace iteos\Models;

use iteos\Traits\Uuid;
use Illuminate\Database\Eloquent\Model;

class Delivery extends Model
{
    use Uuid;

    protected $fillable = [
        'do_ref',
        'order_ref',
        'del_service_id',
        'delivery_cost',
        'receipt',
        'delivered_at',
        'status_id',
        'created_by',
        'updated_by',
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

    public function Statuses()
    {
        return $this->belongsTo(Status::class,'status_id');
    }

    public function Sales()
    {
        return $this->belongsTo(Sale::class,'order_ref','order_ref');
    }

    public function Courier()
    {
        return $this->belongsTo(DeliveryService::class,'del_service_id');
    }

    public function Child()
    {
        return $this->hasMany(DeliveryItem::class,'delivery_id');
    }
}
