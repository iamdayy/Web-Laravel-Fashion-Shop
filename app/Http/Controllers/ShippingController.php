<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Shipping;
use App\Models\Order;
use Illuminate\Support\Facades\Auth;

class ShippingController extends Controller
{
    /**
     * Display the shipping information for the order.
     *
     * @param int $orderId
     * @return \Illuminate\View\View
     */
    public function showShippingInfo($orderId)
    {
        $shipping = Shipping::where('order_id', $orderId)->firstOrFail();
        return view('shipping.show', [
            'shipping' => $shipping,
            'title' => 'Shipping Information',
        ]);
    }
    /**
     * Update the shipping information for the order.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $orderId
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateShippingInfo(Request $request, $orderId)
    {
        $shipping = Shipping::where('order_id', $orderId)->firstOrFail();
        $shipping->update($request->only([
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
            'tracking_number'
        ]));
        return redirect()->route('shipping.show', ['orderId' => $orderId])
            ->with('success', 'Shipping information updated successfully.');
    }
    /**
     * show the form to create a new shipping record.
     */
    public function createShippingForm($orderId)
    {
        $order = Order::findOrFail($orderId);
        // Ensure the order belongs to the authenticated user
        if ($order->user_id !== Auth::id()) {
            return redirect()->back()->withErrors(['error' => 'Unauthorized access to this order.']);
        }
        return view('user.shipping.index', compact('order'));
    }
    /**
     * Create a new shipping record for the order.
     */
    public function createShipping(Request $request, $orderId)
    {
        $shipping = Shipping::create([
            'order_id' => $orderId,
            'state' => $request->input('state'),
            'city' => $request->input('city'),
            'district' => $request->input('district'),
            'subdistrict' => $request->input('subdistrict'),
            'postal_code' => $request->input('postal_code'),
            'address' => $request->input('address'),
            'phone' => $request->input('phone'),
            'courier' => $request->input('courier', 'standard'),
            'shipping_cost' => $request->input('shipping_cost', 0.00),
            'status' => 'pending',
            'shipped_at' => null,
            'delivered_at' => null
        ]);
        return redirect()->route('orders.show', ['id' => $orderId])
            ->with('success', 'Shipping information created successfully.')
            ->with('shipping', $shipping);
    }
}
