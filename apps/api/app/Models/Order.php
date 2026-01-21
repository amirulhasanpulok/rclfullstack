<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class Order extends BaseModel
{
    use HasFactory;

    protected $fillable = [
        'order_number',
        'user_id',
        'total_amount',
        'subtotal',
        'tax_amount',
        'shipping_cost',
        'status',
        'payment_status',
        'tracking_id',
        'courier',
        'notes',
    ];

    protected $casts = [
        'total_amount' => 'decimal:2',
        'subtotal' => 'decimal:2',
        'tax_amount' => 'decimal:2',
        'shipping_cost' => 'decimal:2',
    ];

    /**
     * Get the user who placed the order
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get order items
     */
    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    /**
     * Get order statuses (history)
     */
    public function statusHistory()
    {
        return $this->hasMany(OrderStatus::class);
    }

    /**
     * Generate unique order number
     */
    public static function generateOrderNumber()
    {
        $prefix = 'ORD';
        $timestamp = now()->format('YmdHis');
        $random = mt_rand(1000, 9999);
        return $prefix . $timestamp . $random;
    }

    /**
     * Mark order as paid
     */
    public function markAsPaid()
    {
        $this->update(['payment_status' => 'paid']);
    }

    /**
     * Mark order as shipped
     */
    public function markAsShipped($trackingId = null, $courier = null)
    {
        $this->update([
            'status' => 'shipped',
            'tracking_id' => $trackingId,
            'courier' => $courier,
        ]);
    }
}
