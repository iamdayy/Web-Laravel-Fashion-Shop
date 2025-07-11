<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    public static function pendingOrders()
    {
        $pendingOrders = DB::table('orders')->where('status', 'pending')->get();
        return count($pendingOrders);
    }
    public function index()
    {
        // mengambil data dari table orders
        $orders = Order::with(['items', 'user'])->paginate(20);
        $pendingOrders = DB::table('orders')->where('status', 'pending')->get();
        return view('admin.orders.index', [
            'orders' => $orders,
            'countPendingOrders' => OrderController::pendingOrders(),
            'pendingOrders' => $pendingOrders
        ]);
    }
    public function userShow()
    {
        // mengambil data dari table orders
        $orders = Order::with(['items', 'user', 'shipping', 'payment'])
            ->where('user_id', Auth::user()->id)->orderBy('updated_at', 'desc')->paginate(10);
        $pendingOrders = DB::table('orders')->where('status', 'pending')->get();
        return view('user.orders.index', [
            'orders' => $orders,
            'pendingOrders' => $pendingOrders
        ]);
    }

    public function show($id)
    {
        // mengambil data dari table orders
        $order = Order::with(['items', 'user', 'shipping', 'payment'])->findOrFail($id);
        if ($order->user_id !== Auth::user()->id) {
            return redirect()->back()->withErrors(['error' => 'Unauthorized access to this order.']);
        }
        return view('user.orders.show', [
            'order' => $order,
        ]);
    }

    public function adminShow($id)
    {
        // mengambil data dari table orders
        $order = Order::with(['items', 'user', 'shipping', 'payment'])->findOrFail($id);
        return view('admin.orders.show', [
            'order' => $order,
            'countPendingOrders' => OrderController::pendingOrders(),
        ]);
    }


    public function changeStatus(string $id, string $status)
    {
        DB::table('orders')->where('id', $id)->update([
            'status' => $status,
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
        ]);

        return redirect('/admin/orders');
    }
}
