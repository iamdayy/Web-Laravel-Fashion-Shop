@extends('user.layout')
@section('content.user')
    <div class="container">
        @php
            $grossAmount = 0;
            foreach ($order->items as $item) {
                $grossAmount += $item->price * $item->pivot->quantity;
            }
        @endphp
        <h1 class="my-4">Order Details</h1>
        <div class="mb-4 card">
            <div class="card-body">
                <h5 class="card-title">Order ID: {{ $order->id }}</h5>
                <p class="card-text">Status: {{ $order->status }}</p>
                <p class="card-text">Total Amount: {{ number_format($grossAmount, 2) }} IDR</p>
                <p class="card-text">Shipping Cost:
                    {{ $order->shipping ? number_format($order->shipping->shipping_cost, 2) : '0.00' }}
                    IDR</p>
                <p class="card-text">Created At: {{ $order->created_at->format('d M Y H:i') }}</p>
            </div>
        </div>

        <h2 class="my-4">Items</h2>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Product</th>
                    <th>Quantity</th>
                    <th>Price</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($order->items as $item)
                    <tr>
                        <td>{{ $item->name }}</td>
                        <td>{{ $item->pivot->quantity }}</td>
                        <td>{{ number_format($item->price, 2) }} IDR</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <!-- Display Shipping Address -->
        <h2 class="my-4">Shipping Address</h2>
        @if ($order->shipping)
            <div class="mb-4 card">
                <div class="card-body">
                    <h5 class="card-title">Shipping Address</h5>
                    <p class="card-text">{{ $order->user->name }}</p>
                    <p class="card-text">{{ $order->shipping->address }}</p>
                    <p class="card-text">{{ $order->shipping->city }}, {{ $order->shipping->state }}</p>
                    <p class="card-text">Postal Code: {{ $order->shipping->postal_code }}</p>
                    <p class="card-text">Phone: {{ $order->shipping->phone }}</p>
                </div>
            </div>
        @else
            <a href="{{ route('shipping.create', $order->id) }}" class="mb-4 btn btn-primary">Add Shipping Address</a>
        @endif
        <!-- Display Payment Details -->

        <h2 class="my-4">Payment Details</h2>
        @if ($order->payment)
            <div class="mb-4 card">
                <div class="card-body">
                    <h5 class="card-title">Payment Method: {{ $order->payment->payment_method }}</h5>
                    <p class="card-text">Amount: {{ number_format($order->payment->amount, 2) }} IDR</p>
                    <p class="card-text">Status: {{ $order->payment->status }}</p>
                    <p class="card-text">Transaction ID: {{ $order->payment->transaction_id }}</p>
                    <p class="card-text">Paid At:
                        {{ $order->payment->paid_at ? $order->payment->paid_at->format('d M Y H:i') : 'Not Paid' }}</p>
                </div>
                <button class="btn btn-primary" id="pay-button">
                    Pay Now
                </button>
            </div>
        @elseif (empty($order->shipping))
            <div class="alert alert-warning" role="alert">
                Please add a shipping address before processing payment.
            </div>
        @else
            <a href="{{ route('payment.process', $order->id) }}" class="mb-4 btn btn-success">Process Payment</a>
        @endif

    </div>

    <?php
    $snapToken;
    if (empty($snap_token)) {
        $snapToken = '';
    }
    if ($order->payment && $order->payment->snap_token) {
        $snapToken = $order->payment->snap_token;
    }
    ?>

    <script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ config('midtrans.client_key') }}">
    </script>
    <script>
        const payButton = document.querySelector('#pay-button');
        payButton.addEventListener('click', function(e) {
            e.preventDefault();

            snap.pay('{{ $snapToken }}', {
                // Optional
                onSuccess: function(result) {
                    /* You may add your own js here, this is just example */
                    // document.getElementById('result-json').innerHTML += JSON.stringify(result, null, 2);
                    console.log(result)
                },
                // Optional
                onPending: function(result) {
                    /* You may add your own js here, this is just example */
                    // document.getElementById('result-json').innerHTML += JSON.stringify(result, null, 2);
                    console.log(result)
                },
                // Optional
                onError: function(result) {
                    /* You may add your own js here, this is just example */
                    // document.getElementById('result-json').innerHTML += JSON.stringify(result, null, 2);
                    console.log(result)
                }
            });
        });
    </script>
@endsection
