<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class OrderStatus extends BaseModel
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'status',
        'description',
    ];

    /**
     * Get the order this status belongs to
     */
    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}
