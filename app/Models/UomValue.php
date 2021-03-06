<?php

namespace iteos\Models;

use Illuminate\Database\Eloquent\Model;

class UomValue extends Model
{
    protected $fillable = [
        'type_id',
        'is_parent',
        'parent_id',
        'name',
        'value',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'is_parent' => 'boolean',
    ];

    public function Author()
    {
        return $this->belongsTo(User::class,'created_by');
    }

    public function Editor()
    {
        return $this->belongsTo(User::class,'updated_by');
    }

    public function Parent()
    {
        return $this->belongsTo(UomCategory::class,'type_id');
    }
}
