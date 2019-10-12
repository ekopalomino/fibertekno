<?php

namespace Erp\Models;

use Illuminate\Database\Eloquent\Model;

class InternalTransfer extends Model
{
    protected $fillable = [
        'order_ref',
        'from_id',
        'to_id',
        'status_id',
        'created_by',
        'updated_by',
        'received_by',
    ];

    public function From()
    {
        return $this->belongsTo(Warehouse::class,'from_id');
    }

    public function To()
    {
        return $this->belongsTo(Warehouse::class,'to_id');
    }

    public function Statuses()
    {
        return $this->belongsTo(Status::class,'status_id');
    }

    public function Sender()
    {
        return $this->belongsTo(User::class,'created_by');
    }

    public function Receiver()
    {
        return $this->belongsTo(User::class,'updated_by');
    }

    public function Child()
    {
        return $this->hasMany(InternalItems::class,'id');
    }
}
