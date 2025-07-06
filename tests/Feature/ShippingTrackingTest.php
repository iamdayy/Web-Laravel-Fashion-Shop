<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Order;
use App\Models\Shipping;
use App\Models\Item;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Http;

class ShippingTrackingTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    protected $user;
    protected $order;
    protected $shipping;

    protected function setUp(): void
    {
        parent::setUp();

        // Create a user and authenticate
        $this->user = User::factory()->create();
        $this->actingAs($this->user);

        // Create an order with shipping
        $this->order = Order::factory()->create([
            'user_id' => $this->user->id,
            'status' => 'shipped'
        ]);

        $this->shipping = Shipping::factory()->create([
            'order_id' => $this->order->id,
            'tracking_number' => 'JZ1076592577',
            'courier' => 'J&T',
            'status' => 'shipped'
        ]);

        // Create some items for the order
        $items = Item::factory()->count(2)->create();
        foreach ($items as $item) {
            $this->order->items()->attach($item->id, ['quantity' => 1]);
        }
    }

    public function test_tracking_page_displays_correctly()
    {
        // Mock the API response
        $mockResponse = [
            'meta' => [
                'message' => 'Success Get Waybill',
                'code' => 200,
                'status' => 'success'
            ],
            'data' => [
                'delivered' => true,
                'summary' => [
                    'courier_code' => 'J&T',
                    'courier_name' => 'J&T Express',
                    'waybill_number' => 'JZ1076592577',
                    'service_code' => 'EZ',
                    'waybill_date' => '2025-06-11',
                    'shipper_name' => 'abyaad batik',
                    'receiver_name' => 'mawi',
                    'origin' => 'KAJEN PEKALONGAN',
                    'destination' => 'BANYUWANGI',
                    'status' => 'DELIVERED'
                ],
                'manifest' => [
                    [
                        'manifest_code' => '200',
                        'manifest_description' => 'Paket telah diterima',
                        'manifest_date' => '2025-06-13',
                        'manifest_time' => '09:21:26',
                        'city_name' => 'BANYUWANGI - KALIPURO'
                    ]
                ],
                'delivery_status' => [
                    'status' => 'DELIVERED',
                    'pod_receiver' => 'mawi',
                    'pod_date' => '2025-06-13',
                    'pod_time' => '09:21:26'
                ]
            ]
        ];

        Http::fake([
            'rajaongkir.komerce.id/api/v1/track/waybill' => Http::response($mockResponse, 200)
        ]);

        // Visit the tracking page
        $response = $this->get(route('shipping.showTracking', $this->order->id));

        // Assert the response is successful
        $response->assertStatus(200);

        // Assert that key elements are present
        $response->assertSee('Track Shipping');
        $response->assertSee('Order Information');
        $response->assertSee('Shipping Status');
        $response->assertSee('Tracking Information');
        $response->assertSee('JZ1076592577');
        $response->assertSee('J&T Express');
        $response->assertSee('DELIVERED');
        $response->assertSee('Paket telah diterima');
    }

    public function test_tracking_requires_authentication()
    {
        // Log out the user
        auth()->logout();

        // Try to access the tracking page
        $response = $this->get(route('shipping.showTracking', $this->order->id));

        // Should redirect to login
        $response->assertRedirect(route('login'));
    }

    public function test_user_can_only_track_own_orders()
    {
        // Create another user and order
        $otherUser = User::factory()->create();
        $otherOrder = Order::factory()->create([
            'user_id' => $otherUser->id
        ]);

        // Try to access the other user's order tracking
        $response = $this->get(route('shipping.showTracking', $otherOrder->id));

        // Should redirect back with error
        $response->assertRedirect();
        $response->assertSessionHas('error', 'Unauthorized access to this order.');
    }

    public function test_tracking_without_tracking_number()
    {
        // Update shipping to remove tracking number
        $this->shipping->update(['tracking_number' => null]);

        // Visit the tracking page
        $response = $this->get(route('shipping.showTracking', $this->order->id));

        // Should still display the page but with error message
        $response->assertStatus(200);
        $response->assertSee('Tracking number not available for this order.');
    }

    public function test_tracking_api_failure()
    {
        // Mock API failure
        Http::fake([
            'rajaongkir.komerce.id/api/v1/track/waybill' => Http::response([], 500)
        ]);

        // Visit the tracking page
        $response = $this->get(route('shipping.showTracking', $this->order->id));

        // Should still display the page but with error message
        $response->assertStatus(200);
        $response->assertSee('Failed to fetch tracking information. Please try again later.');
    }
}
