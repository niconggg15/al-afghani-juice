<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_number',
        'user_id',
        'is_demo',
        'customer_name',
        'customer_phone',
        'status',
        'payment_method',
        'subtotal',
        'tax',
        'discount',
        'total',
        'notes',
        'confirmed_at',
        'completed_at',
    ];

    protected $casts = [
        'is_demo' => 'boolean',
        'subtotal' => 'decimal:2',
        'tax' => 'decimal:2',
        'discount' => 'decimal:2',
        'total' => 'decimal:2',
        'confirmed_at' => 'datetime',
        'completed_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function payment()
    {
        return $this->hasOne(Payment::class);
    }

    public function scopeVisibleToCurrentAccount(Builder $query)
    {
        $user = auth()->user();

        return $query->where('is_demo', $user && $user->isDemoAccount());
    }

    public static function generateOrderNumber()
    {
        $prefix = 'KJ';
        $date = now()->format('Ymd');
        $lastOrder = self::where('order_number', 'like', $prefix . $date . '%')
            ->orderBy('id', 'desc')
            ->first();

        $sequence = $lastOrder ? (int) substr($lastOrder->order_number, -4) + 1 : 1;
        return $prefix . $date . str_pad($sequence, 4, '0', STR_PAD_LEFT);
    }
}
