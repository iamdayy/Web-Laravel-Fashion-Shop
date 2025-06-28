<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Shipping extends Model
{
    use HasFactory;
    protected $table = 'shippings';
    public $timestamps = true;
    protected $fillable = [
        'order_id',
        'state',
        'city',
        'district',
        'subdistrict',
        'postal_code',
        'address',
        'phone',
        'courier',
        'shipping_cost',
        'status',
        'shipped_at',
        'delivered_at',
        'tracking_number',
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
