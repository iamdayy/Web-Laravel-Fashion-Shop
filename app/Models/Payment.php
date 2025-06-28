<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;
    protected $table = 'payments';
    public $timestamps = true;
    protected $fillable = [
        'order_id',
        'payment_method',
        'amount',
        'currency',
        'status',
        'transaction_id',
        'paid_at',
        'snap_token', // Token for Midtrans Snap
    ];
    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id');
    }
    public function user()
    {
        return $this->order->user();
    }
    public function items()
    {
        return $this->order->items();
    }
}
