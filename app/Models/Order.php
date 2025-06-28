<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    public function items()
    {
        return $this->belongsToMany(Item::class, 'items_orders')
            ->withPivot('quantity', 'review_id')
            ->withTimestamps();
    }
    public function shipping()
    {
        return $this->hasOne(Shipping::class, 'order_id');
    }
    public function payment()
    {
        return $this->hasOne(Payment::class, 'order_id');
    }
    public function reviews()
    {
        return $this->items()
            ->join('reviews', 'items_orders.review_id', '=', 'reviews.id')
            ->select('reviews.*');
    }
    public function totalQuantity()
    {
        return $this->items()
            ->selectRaw('SUM(items_orders.quantity) as total_quantity')
            ->groupBy('orders.id');
    }
    use HasFactory;
}
