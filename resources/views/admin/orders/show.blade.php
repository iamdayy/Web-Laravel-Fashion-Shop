@extends('admin.layout')
@section('content_admin')

    <body>
        <div class="container-fluid">
            <div class="row">
                @include('admin.components.sidebar')

                @php
                    $totalPrice = $order->items->sum(function ($item) {
                        return $item->price * $item->pivot->quantity;
                    });
                @endphp

                <main class="my-5 col-md-9 ms-sm-auto col-lg-10 px-md-4">
                    <div class="container-fluid">
                        <h2>Order Details</h2>
                        <div class="mb-4 card">
                            <div class="card-body">
                                <h5 class="card-title">Order #{{ $order->id }}</h5>
                                <p><strong>user:</strong> {{ $order->user->name }}</p>
                                <p><strong>Email:</strong> {{ $order->user->email }}</p>
                                <p><strong>Order Date:</strong> {{ $order->created_at->format('d/m/Y H:i') }}</p>
                                <p><strong>Status:</strong> {{ ucfirst($order->status) }}</p>
                                <p><strong>Total:</strong> Rp. {{ number_format($totalPrice, 2) }}</p>
                            </div>
                        </div>

                        <h4>Order Items</h4>
                        <div class="table-responsive">
                            <table class="table table-striped table-sm">
                                <thead>
                                    <tr>
                                        <th scope="col">#</th>
                                        <th scope="col">items</th>
                                        <th scope="col">Qty</th>
                                        <th scope="col">Unit Price</th>
                                        <th scope="col">Subtotal</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($order->items as $index => $item)
                                        <tr>
                                            <td>{{ $index + 1 }}</td>
                                            <td>{{ $item->name }}</td>
                                            <td>{{ $item->pivot->quantity }}</td>
                                            <td>Rp. {{ number_format($item->price, 2) }}</td>
                                            <td>Rp. {{ number_format($item->price * $item->pivot->quantity, 2) }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <h4>Shipping Information</h4>
                        @if ($order->shipping)
                            <div class="mb-4 card">
                                <div class="card-body">
                                    <p><strong>Courier:</strong> {{ ucfirst($order->shipping->courier) }}</p>
                                    <p><strong>Tracking Number:</strong> {{ $order->shipping->tracking_number }}</p>
                                    <p><strong>Status:</strong> {{ ucfirst($order->shipping->status) }}</p>

                                    <p><strong>Address:</strong> {{ $order->shipping->address }}</p>
                                    <p><strong>City:</strong> {{ $order->shipping->city }}</p>
                                    <p><strong>Province:</strong> {{ $order->shipping->province }}</p>
                                    <p><strong>Postal Code:</strong> {{ $order->shipping->postal_code }}</p>
                                    <p><strong>Phone:</strong> {{ $order->shipping->phone }}</p>
                                    <p><strong>Shipping Cost:</strong> Rp. {{ number_format($order->shipping->cost, 2) }}
                                    </p>

                                    <!-- Modal trigger button -->
                                    <button type="button" class="btn btn-primary btn-lg" data-bs-toggle="modal"
                                        data-bs-target="#setTrackingModal">
                                        Set Tracking Number
                                    </button>

                                    <!-- Modal Body -->
                                    <!-- if you want to close by clicking outside the modal, delete the last endpoint:data-bs-backdrop and data-bs-keyboard -->
                                    <div class="modal fade" id="setTrackingModal" tabindex="-1" data-bs-backdrop="static"
                                        data-bs-keyboard="false" role="dialog" aria-labelledby="modalTitleId"
                                        aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered modal-sm"
                                            role="document">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="modalTitleId">
                                                        Set Tracking Number
                                                    </h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                        aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">Body</div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary"
                                                        data-bs-dismiss="modal">
                                                        Close
                                                    </button>
                                                    <button type="button" class="btn btn-primary">Save</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        @else
                            <div class="alert alert-warning" role="alert">
                                No shipping information available for this order.
                            </div>
                        @endif
                        <h4>Payment Information</h4>
                        @if ($order->payment)
                            <div class="mb-4 card">
                                <div class="card-body">
                                    <p><strong>Payment Method:</strong> {{ ucfirst($order->payment->method) }}</p>
                                    <p><strong>Transaction ID:</strong> {{ $order->payment->transaction_id }}</p>
                                    <p><strong>Status:</strong> {{ ucfirst($order->payment->status) }}</p>
                                </div>
                            </div>
                        @else
                            <div class="alert alert-warning" role="alert">
                                No payment information available for this order.
                            </div>
                        @endif
                        <div class="d-flex justify-content-between">
                            <h4>Total Price: Rp. {{ number_format($totalPrice, 2) }}</h4>
                            @if ($order->status == 'pending')
                                <div class="gap-2 btn-group" role="group">
                                    <form
                                        action="{{ route('admin.orders.changeStatus', ['id' => $order->id, 'status' => 'success']) }}"
                                        method="POST">
                                        @csrf
                                        <button type="submit" class="btn btn-success">Mark as Success</button>
                                    </form>
                                    <form
                                        action="{{ route('admin.orders.changeStatus', ['id' => $order->id, 'status' => 'failed']) }}"
                                        method="POST">
                                        @csrf
                                        <button type="submit" class="btn btn-danger">Mark as Failed</button>
                                    </form>
                                </div>
                            @endif
                        </div>
                        <div class="mt-4 d-flex justify-content-between">
                            <a href="{{ route('admin.orders.index') }}" class="btn btn-secondary">Back to Orders</a>
                        </div>
                    </div>
                </main>
            </div>
        </div>
    @endsection
