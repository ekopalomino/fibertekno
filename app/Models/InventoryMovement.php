<?php

namespace Erp\Models;

use Illuminate\Database\Eloquent\Model;

class InventoryMovement extends Model
{
    protected $fillable = [
        'type',
        'reference_id',
        'inventory_id',
        'product_id',
        'warehouse_id',
        'incoming',
        'outgoing',
        'remaining',
        'notes',
    ];
    
    public function Items()
    {
        return $this->belongsTo(Product::class,'product_id');
    }

    public function Child()
    {
        return $this->belongsTo(Inventory::class,'inventory_id');
    }

    public function Locations()
    {
        return $this->belongsTo(Warehouse::class,'warehouse_id');
    }
}
