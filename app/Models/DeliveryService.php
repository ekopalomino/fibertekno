<?php

namespace Erp\Models;

use Illuminate\Database\Eloquent\Model;

class DeliveryService extends Model
{
    protected $fillable = [
        'delivery_name',
        'status_id',
        'created_by',
        'updated_by',
    ];

    public function Statuses()
    {
        return $this->belongsTo(Status::class,'status_id');
    }
}
