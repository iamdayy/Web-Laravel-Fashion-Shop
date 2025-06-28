<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class OrderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \App\Models\Order::factory(50)->create()->each(function ($order) {
            $items = \App\Models\Item::inRandomOrder()->take(rand(1, 5))->get();
            foreach ($items as $item) {
                $order->items()->attach($item->id, [
                    'quantity' => rand(1, 3),
                ]);
            }
        });
    }
}
