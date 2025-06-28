<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use \Midtrans\Snap;
use \Midtrans\Config;
use App\Models\Payment;

class PaymentController extends Controller
{
    public function __construct()
    {
        Config::$serverKey = config('midtrans.server_key');
        Config::$isProduction = config('midtrans.is_production');
        Config::$isSanitized = config('midtrans.is_sanitized');
        Config::$is3ds = config('midtrans.is_3ds');
    }
    /**
     * Process the payment for the order.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function processPayment($id)
    {
        $grossAmount = 0;
        $order = Order::findOrFail($id);
        // Calculate the total amount for the order
        foreach ($order->items as $item) {
            $grossAmount += $item->price * $item->pivot->quantity;
        }
        // Ensure the order belongs to the authenticated user
        if ($order->user_id !== Auth::id()) {
            return redirect()->back()->withErrors(['error' => 'Unauthorized access to this order.']);
        }
        $params = [
            'transaction_details' => [
                'order_id' => $id,
                'gross_amount' => $grossAmount, // Total amount to be paid
            ],
            'customer_details' => [
                'first_name' => Auth::user()->name,
                'email' => Auth::user()->email,
            ],
            'item_details' => $order->items->map(function ($item) {
                return [
                    'id' => $item->id,
                    'price' => $item->price,
                    'quantity' => $item->pivot->quantity,
                    'name' => $item->name,
                ];
            })->toArray(),
            'enabled_payments' => ['gopay', 'bank_transfer', 'credit_card', 'shopeepay'],
            // 'expiry' => [
            //     'start_time' => now()->addHour(1)->toDateTimeLocalString('yyyy-MM-dd hh:mm:ss Z'),
            //     'unit' => 'minute',
            //     'duration' => 1,
            // ],
        ];
        $snapToken = Snap::getSnapToken($params);
        // save payment details to the database
        Payment::create([
            'order_id' => $id,
            'payment_method' => 'midtrans',
            'amount' => $grossAmount,
            'currency' => 'IDR',
            'status' => 'pending',
            'transaction_id' => null, // This will be filled after payment is completed
            'paid_at' => null,
            'snap_token' => $snapToken, // Store the Snap token
        ]);
        return redirect()->route('orders.show', $id)->with('snap_token', $snapToken);
    }

    public function success()
    {
        // Display a success message after payment
        return view('user.payment.success');
    }
}
