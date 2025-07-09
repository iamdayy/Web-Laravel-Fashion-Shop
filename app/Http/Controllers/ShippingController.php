<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Shipping;
use App\Models\Order;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;

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
        if (!$order) {
            return redirect()->back()->withErrors(['error' => 'Order not found.']);
        }
        // Check if the order is already shipped
        if ($order->shipping) {
            return redirect()->back()->withErrors(['error' => 'Shipping information already exists for this order.']);
        }
        // Ensure the order belongs to the authenticated user
        if ($order->user_id !== Auth::id()) {
            return redirect()->back()->withErrors(['error' => 'Unauthorized access to this order.']);
        }
        return view('user.shipping.index', compact('order'));
    }
    /**
     * Create a new shipping record for the order.
     */
    public function createShipping(Request $request, $id)
    {
        $shipping = Shipping::create([
            'order_id' => $id,
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
        return redirect()->route('orders.show', ['id' => $id])
            ->with('success', 'Shipping information created successfully.')
            ->with('shipping', $shipping);
    }
    /**
     * Get the destination for shipping.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getDestination(Request $request)
    {
        $query = $request->input('query');
        if (!$query) {
            return response()->json(['error' => 'Query parameter is required'], 400);
        }
        $url = 'https://rajaongkir.komerce.id/api/v1/destination/domestic-destination?search=' . urlencode($query);
        $response =  Http::withHeaders([
            'key' => config('rajaongkir_api.key'),
            'Content-Type' => 'application/json',
        ])->get($url);
        if (!$response->successful()) {
            return response()->json(['error' => 'Failed to fetch data from RajaOngkir'], $response->status());
        }
        $data = $response->json();
        if (isset($data['data'])) {
            return response()->json($data['data']);
        } else {
            return response()->json(['error' => 'No results found'], 404);
        }
    }
    /**
     * calculate shipping cost based on the selected destination and courier.
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function calculateShippingCost(Request $request)
    {
        $request->validate([
            'destination' => 'required|integer',
            'weight' => 'required|integer|min:1', // Weight must be a positive integer
            'courier' => 'required|string|in:jne,pos,tiki,jnt,sicepat,tiki,anteraja', // Valid couriers
        ]);
        $url = 'https://rajaongkir.komerce.id/api/v1/calculate/domestic-cost';
        $response = Http::withHeaders([
            'key' => config('rajaongkir_api.key'),
            'Content-Type' => 'application/json',
        ])->asForm()->post($url, [
            'origin' => env('RAJAONGKIR_ORIGIN'), // Default origin ID, can be set in .env
            'destination' => $request->input('destination', 65421),
            'weight' => $request->input('weight'),
            'courier' => $request->input('courier'),
        ]);
        if (!$response->successful()) {
            return response()->json(['error' => 'Failed to fetch shipping cost'], $response->status());
        }
        $data = $response->json();
        if (isset($data['data']) && is_array($data['data'])) {
            return response()->json($data['data']);
        } else {
            return response()->json(['error' => 'No shipping options found'], 404);
        }
    }
    /**
     * Set the shipping status to shipped.
     * @param int $id
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function setShipped($id, Request $request)
    {
        $request->validate([
            'tracking_number' => 'string|max:255', // Optional tracking number
        ]);
        $shipping = Shipping::where('order_id', $id)->firstOrFail();
        $shipping->status = 'shipped';
        $shipping->shipped_at = now();
        $shipping->tracking_number = $request->input('tracking_number'); // Optional
        $shipping->save();
        return redirect()->route('orders.show', ['id' => $id]);
    }
    /**
     * Set the shipping status to delivered.
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function setDelivered($id)
    {
        $shipping = Shipping::where('order_id', $id)->firstOrFail();
        $shipping->status = 'delivered';
        $shipping->delivered_at = now();
        $shipping->save();
        return redirect()->route('orders.show', ['id' => $id]);
    }
    /**
     * track the shipping status.
     * @param int $id
     * @return
     */
    public function trackShipping($id)
    {
        $order = Order::findOrFail($id);

        // Check if user is authenticated
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Please log in to track your order.');
        }

        // Check if the order belongs to the authenticated user
        if ($order->user_id !== Auth::id()) {
            return redirect()->back()->with('error', 'Unauthorized access to this order.');
        }

        $shipping = Shipping::where('order_id', $id)->firstOrFail();

        if (!$shipping->tracking_number) {
            return view('shipping.show', [
                'order' => $order->load('shipping'),
                'tracking_info' => [],
                'title' => 'Track Shipping',
            ])->with('error', 'Tracking number not available for this order.');
        }

        $url = 'https://rajaongkir.komerce.id/api/v1/track/waybill';
        $response = Http::withHeaders([
            'key' => config('rajaongkir_api.key'),
            'Content-Type' => 'application/json',
        ])->asForm()->post($url, [
            'courier' => $shipping->courier,
            'awb' => $shipping->tracking_number,
        ]);

        if (!$response->successful()) {
            return view('shipping.show', [
                'order' => $order->load('shipping'),
                'tracking_info' => [],
                'title' => 'Track Shipping',
            ])->with('error', 'Failed to fetch tracking information. Please try again later.');
        }

        $data = $response->json();
        return view('shipping.show', [
            'order' => $order->load('shipping'),
            'tracking_info' => $data['data'] ?? [],
            'title' => 'Track Shipping',
        ]);
    }
}
