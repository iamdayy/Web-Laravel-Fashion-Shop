<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    public function  orders()
    {
        return $this->belongsToMany(Order::class, 'items_orders', 'item_id', 'order_id')
            ->withPivot('quantity', 'review_id')
            ->withTimestamps();
    }
    public function reviews()
    {
        return $this->Orders()
            ->join('reviews', 'items_orders.review_id', '=', 'reviews.id')
            ->select('reviews.*');
    }
    public function sold()
    {
        return  $this->Orders()
            ->selectRaw('SUM(items_orders.quantity) as total_sold')
            ->groupBy('item_id');
    }
    public function rating()
    {
        return $this->Reviews()
            ->selectRaw('AVG(reviews.rating) as average_rating')
            ->groupBy('item_id');
    }
    public function Cart()
    {
        return $this->hasMany(Cart::class);
    }
    use HasFactory;

    protected $table = 'items';
    public $timestamps = true;

    protected $fillable = [
        'photo',
        'name',
        'description',
        'stock',
        'price',
        'category',
        'created_at',
        'updated_at',
    ];
}
