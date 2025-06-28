<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Order;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CartController extends Controller
{
    public function index()
    {
        $carts = Cart::with(['item', 'user'])->where('user_id', Auth::user()->id)
            ->get();
        // $carts = DB::table('carts')
        //     ->where('user_id', Auth::user()->id)
        //     ->get();
        $subtotal = 0;
        foreach ($carts as $cart) {
            $subtotal += $cart->item->price * $cart->quantity;
        }
        return view('user.cart.index', [
            'carts' => $carts,
            'subtotal' => $subtotal,
        ]);
    }
    public function addToCart(Request $request)
    {
        $itemExist = DB::table('carts')
            ->where('user_id', Auth::user()->id)
            ->where('item_id', $request->item_id)
            ->get();
        // var_dump(sizeof($itemExist));
        // exit;
        if (sizeof($itemExist)) {
            $count = $itemExist[0]->quantity + (int)$request->quantity;
            DB::table('carts')->where('id', $itemExist[0]->id)->update([
                'quantity' => $count,
            ]);
            return redirect("/products/show/$request->item_id");
        }
        DB::table('carts')->insert([
            'id' => $request->id,
            'user_id' => $request->user_id,
            'item_id' => $request->item_id,
            'quantity' => $request->quantity,
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
        ]);

        return redirect("/products/show/$request->item_id");
    }
    public function updateCart(Request $request)
    {
        $item = DB::table('carts')
            ->where('user_id', Auth::user()->id)
            ->where('item_id', $request->item_id)
            ->get();
        $count = (int)$request->quantity;
        if (!isset($request->minus) && !isset($request->plus)) {
            $count += 1;
        }
        if (sizeof($item)) {
            DB::table('carts')->where('id', $item[0]->id)->update([
                'quantity' => $count,
            ]);
            return redirect("/cart");
        }
        return redirect("/cart");
    }

    public function checkout(Request $request)
    {
        $carts = Cart::with(['item', 'user'])->where('user_id', Auth::user()->id)
            ->get();
        $subtotal = 0;
        foreach ($carts as $cart) {
            $subtotal += $cart->item->price * $cart->quantity;
        }
        if ($subtotal <= 0) {
            return redirect("/cart")->with('error', 'Your cart is empty!');
        }
        $order = new Order();
        $order->user_id = Auth::user()->id;
        $order->status = 'pending';
        $order->save();

        foreach ($carts as $cart) {
            $order->items()->attach($order->id, [
                'review_id' => null, // Assuming no review is attached at checkout
                'item_id' => $cart->item->id, // Assuming you want to keep track of the cart item
                'quantity' => $cart->quantity,
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
            ]);
        }
        // Clear the cart after checkout
        DB::table('carts')->where('user_id', Auth::user()->id)->delete();
        return redirect("/shipping/$order->id")->with('success', 'Checkout successful! Please fill in your shipping details.');
    }
}
