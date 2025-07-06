@extends('user.layout')
@section('content.user')
    <div class="container mt-4 mb-5 container-1-light w-100 p-relative">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="mb-0 card-title">
                            <i class="fas fa-truck me-2"></i>{{ $title ?? 'Shipping Information' }}
                        </h3>
                    </div>
                    <div class="card-body">
                        @if (session('success'))
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                {{ session('success') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        @endif

                        @if (session('error'))
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                {{ session('error') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        @endif

                        @if (isset($order) && $order->shipping)
                            <!-- Order Information -->
                            <div class="mb-4 row">
                                <div class="col-md-6">
                                    <h5 class="text-primary">Order Information</h5>
                                    <p><strong>Order ID:</strong> #{{ $order->id }}</p>
                                    <p><strong>Order Status:</strong>
                                        <span
                                            class="badge bg-{{ $order->status == 'completed' ? 'success' : ($order->status == 'pending' ? 'warning' : 'secondary') }}">
                                            {{ ucfirst($order->status) }}
                                        </span>
                                    </p>
                                    <p><strong>Order Date:</strong> {{ $order->created_at->format('d M Y H:i') }}</p>
                                </div>
                                <div class="col-md-6">
                                    <h5 class="text-primary">Shipping Details</h5>
                                    <p><strong>Courier:</strong> {{ strtoupper($order->shipping->courier) }}</p>
                                    <p><strong>Shipping Cost:</strong> Rp
                                        {{ number_format($order->shipping->shipping_cost, 0, ',', '.') }}</p>
                                    @if ($order->shipping->tracking_number)
                                        <p><strong>Tracking Number:</strong>
                                            <span class="badge bg-info">{{ $order->shipping->tracking_number }}</span>
                                        </p>
                                    @endif
                                </div>
                            </div>
                            <!-- Tracking Information -->
                            @if (isset($tracking_info) && !empty($tracking_info))
                                <div class="mb-4 row">
                                    <div class="col-12">
                                        <h5 class="text-primary">Tracking Information</h5>
                                        <div class="card">
                                            <div class="card-body">
                                                @if (isset($tracking_info['summary']))
                                                    <div class="tracking-details">
                                                        <!-- Tracking Summary -->
                                                        <div class="mb-4 row">
                                                            <div class="col-md-6">
                                                                <h6 class="text-info">Tracking Summary</h6>
                                                                <p><strong>Waybill Number:</strong>
                                                                    <span
                                                                        class="badge bg-primary">{{ $tracking_info['summary']['waybill_number'] ?? 'N/A' }}</span>
                                                                </p>
                                                                <p><strong>Courier:</strong>
                                                                    {{ $tracking_info['summary']['courier_name'] ?? 'N/A' }}
                                                                </p>
                                                                <p><strong>Service:</strong>
                                                                    {{ $tracking_info['summary']['service_code'] ?? 'N/A' }}
                                                                </p>
                                                                <p><strong>Status:</strong>
                                                                    <span
                                                                        class="badge bg-{{ $tracking_info['summary']['status'] == 'DELIVERED' ? 'success' : 'info' }}">
                                                                        {{ $tracking_info['summary']['status'] ?? 'N/A' }}
                                                                    </span>
                                                                </p>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <h6 class="text-info">Shipment Details</h6>
                                                                <p><strong>Shipper:</strong>
                                                                    {{ $tracking_info['summary']['shipper_name'] ?? 'N/A' }}
                                                                </p>
                                                                <p><strong>Receiver:</strong>
                                                                    {{ $tracking_info['summary']['receiver_name'] ?? 'N/A' }}
                                                                </p>
                                                                <p><strong>Origin:</strong>
                                                                    {{ $tracking_info['summary']['origin'] ?? 'N/A' }}</p>
                                                                <p><strong>Destination:</strong>
                                                                    {{ $tracking_info['summary']['destination'] ?? 'N/A' }}
                                                                </p>
                                                                <p><strong>Ship Date:</strong>
                                                                    {{ $tracking_info['summary']['waybill_date'] ?? 'N/A' }}
                                                                </p>
                                                            </div>
                                                        </div>

                                                        <!-- Delivery Status -->
                                                        @if (isset($tracking_info['delivery_status']))
                                                            <div
                                                                class="alert alert-{{ $tracking_info['delivery_status']['status'] == 'DELIVERED' ? 'success' : 'info' }} mb-4">
                                                                <h6 class="alert-heading">
                                                                    <i
                                                                        class="fas fa-{{ $tracking_info['delivery_status']['status'] == 'DELIVERED' ? 'check-circle' : 'truck' }}"></i>
                                                                    Delivery Status
                                                                </h6>
                                                                @if ($tracking_info['delivery_status']['status'] == 'DELIVERED')
                                                                    <p class="mb-1"><strong>Package Delivered!</strong>
                                                                    </p>
                                                                    <p class="mb-1">Received by:
                                                                        <strong>{{ $tracking_info['delivery_status']['pod_receiver'] ?? 'N/A' }}</strong>
                                                                    </p>
                                                                    <p class="mb-0">Delivery Date:
                                                                        <strong>{{ $tracking_info['delivery_status']['pod_date'] ?? 'N/A' }}</strong>
                                                                        at
                                                                        <strong>{{ $tracking_info['delivery_status']['pod_time'] ?? 'N/A' }}</strong>
                                                                    </p>
                                                                @else
                                                                    <p class="mb-0">Status:
                                                                        <strong>{{ $tracking_info['delivery_status']['status'] ?? 'In Transit' }}</strong>
                                                                    </p>
                                                                @endif
                                                            </div>
                                                        @endif

                                                        <!-- Package Details -->
                                                        @if (isset($tracking_info['details']))
                                                            <div class="mb-4 card bg-light">
                                                                <div class="card-body">
                                                                    <h6 class="card-title text-secondary">Package Details
                                                                    </h6>
                                                                    <div class="row">
                                                                        <div class="col-md-6">
                                                                            <p><strong>Weight:</strong>
                                                                                {{ $tracking_info['details']['weight'] ?? 'N/A' }}
                                                                                gram</p>
                                                                            <p><strong>Ship Date:</strong>
                                                                                {{ $tracking_info['details']['waybill_date'] ?? 'N/A' }}
                                                                                {{ $tracking_info['details']['waybill_time'] ?? '' }}
                                                                            </p>
                                                                        </div>
                                                                        <div class="col-md-6">
                                                                            <p><strong>Shipper Address:</strong>
                                                                                {{ $tracking_info['details']['shipper_address1'] ?? 'N/A' }}
                                                                            </p>
                                                                            <p><strong>Receiver Address:</strong>
                                                                                {{ $tracking_info['details']['receiver_address1'] ?? 'N/A' }}
                                                                            </p>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        @endif

                                                        <!-- Tracking History -->
                                                        @if (isset($tracking_info['manifest']) && is_array($tracking_info['manifest']))
                                                            <h6 class="mt-3 mb-3">Tracking History</h6>
                                                            <div class="timeline">
                                                                @foreach (array_reverse($tracking_info['manifest']) as $manifest)
                                                                    <div class="timeline-item">
                                                                        <div
                                                                            class="timeline-marker
                                                                            @if ($manifest['manifest_code'] == '200') bg-success
                                                                            @elseif($manifest['manifest_code'] == '101') bg-warning
                                                                            @else bg-info @endif">
                                                                            <i
                                                                                class="fas fa-
                                                                                @if ($manifest['manifest_code'] == '200') check
                                                                                @elseif($manifest['manifest_code'] == '101') clock
                                                                                @else truck @endif fa-xs text-white"></i>
                                                                        </div>
                                                                        <div class="timeline-content">
                                                                            <h6 class="timeline-title">
                                                                                {{ $manifest['manifest_description'] ?? 'N/A' }}
                                                                            </h6>
                                                                            <p class="timeline-subtitle">
                                                                                <i class="fas fa-calendar-alt"></i>
                                                                                {{ $manifest['manifest_date'] ?? 'N/A' }}
                                                                                @if (isset($manifest['manifest_time']))
                                                                                    <i class="fas fa-clock ms-2"></i>
                                                                                    {{ $manifest['manifest_time'] }}
                                                                                @endif
                                                                            </p>
                                                                            @if (isset($manifest['city_name']))
                                                                                <p class="timeline-location">
                                                                                    <i class="fas fa-map-marker-alt"></i>
                                                                                    {{ $manifest['city_name'] }}
                                                                                </p>
                                                                            @endif
                                                                        </div>
                                                                    </div>
                                                                @endforeach
                                                            </div>
                                                        @endif
                                                    </div>
                                                @else
                                                    <p class="text-muted">No detailed tracking information available.</p>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif

                            <!-- Order Items -->
                            <div class="mb-4 row">
                                <div class="col-12">
                                    <h5 class="text-primary">Order Items</h5>
                                    <div class="table-responsive">
                                        <table class="table table-striped">
                                            <thead>
                                                <tr>
                                                    <th>Product</th>
                                                    <th>Quantity</th>
                                                    <th>Price</th>
                                                    <th>Total</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($order->items as $item)
                                                    <tr>
                                                        <td>{{ $item->name }}</td>
                                                        <td>{{ $item->pivot->quantity }}</td>
                                                        <td>Rp {{ number_format($item->price, 0, ',', '.') }}</td>
                                                        <td>Rp
                                                            {{ number_format($item->price * $item->pivot->quantity, 0, ',', '.') }}
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>

                            <!-- Action Buttons -->
                            <div class="row">
                                <div class="col-12">
                                    <div class="d-flex justify-content-between">
                                        <a href="{{ route('orders.show', $order->id) }}" class="btn btn-secondary">
                                            <i class="fas fa-arrow-left"></i> Back to Order
                                        </a>
                                        @if ($order->shipping->tracking_number)
                                            <a href="{{ route('shipping.showTracking', $order->id) }}"
                                                class="btn btn-primary">
                                                <i class="fas fa-sync-alt"></i> Refresh Tracking
                                            </a>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @elseif(isset($shipping))
                            <!-- Simple Shipping Information Display -->
                            <div class="row">
                                <div class="col-md-6">
                                    <h5 class="text-primary">Shipping Information</h5>
                                    <p><strong>Status:</strong>
                                        <span
                                            class="badge bg-{{ $shipping->status == 'delivered' ? 'success' : ($shipping->status == 'shipped' ? 'info' : 'warning') }}">
                                            {{ ucfirst($shipping->status) }}
                                        </span>
                                    </p>
                                    <p><strong>Courier:</strong> {{ strtoupper($shipping->courier) }}</p>
                                    <p><strong>Shipping Cost:</strong> Rp
                                        {{ number_format($shipping->shipping_cost, 0, ',', '.') }}</p>
                                    @if ($shipping->tracking_number)
                                        <p><strong>Tracking Number:</strong> {{ $shipping->tracking_number }}</p>
                                    @endif
                                </div>
                                <div class="col-md-6">
                                    <h5 class="text-primary">Shipping Address</h5>
                                    <p>{{ $shipping->address }}</p>
                                    <p>{{ $shipping->subdistrict }}, {{ $shipping->district }}</p>
                                    <p>{{ $shipping->city }}, {{ $shipping->state }}</p>
                                    <p>{{ $shipping->postal_code }}</p>
                                    <p><i class="fas fa-phone"></i> {{ $shipping->phone }}</p>
                                </div>
                            </div>
                        @else
                            <div class="py-5 text-center">
                                <i class="mb-3 fas fa-exclamation-triangle fa-3x text-warning"></i>
                                <h4>No Shipping Information Available</h4>
                                <p class="text-muted">Shipping information has not been set up for this order yet.</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        .border-left-primary {
            border-left: 4px solid #007bff !important;
        }

        .status-step {
            padding: 20px;
            transition: all 0.3s ease;
        }

        .status-step.active .status-icon {
            transform: scale(1.1);
        }

        .status-step.current {
            background-color: rgba(0, 123, 255, 0.1);
            border-radius: 10px;
        }

        .timeline {
            position: relative;
            padding-left: 30px;
        }

        .timeline::before {
            content: '';
            position: absolute;
            left: 15px;
            top: 0;
            bottom: 0;
            width: 2px;
            background-color: #dee2e6;
        }

        .timeline-item {
            position: relative;
            margin-bottom: 20px;
        }

        .timeline-marker {
            position: absolute;
            left: -23px;
            top: 5px;
            width: 24px;
            height: 24px;
            border-radius: 50%;
            background-color: #007bff;
            border: 2px solid #fff;
            box-shadow: 0 0 0 3px #dee2e6;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .timeline-marker.bg-success {
            background-color: #28a745;
        }

        .timeline-marker.bg-warning {
            background-color: #ffc107;
        }

        .timeline-marker.bg-info {
            background-color: #17a2b8;
        }

        .timeline-content {
            background-color: #f8f9fa;
            padding: 15px;
            border-radius: 8px;
            border-left: 3px solid #007bff;
        }

        .timeline-title {
            margin-bottom: 5px;
            color: #495057;
            font-weight: 600;
        }

        .timeline-subtitle {
            color: #6c757d;
            font-size: 0.9em;
            margin-bottom: 5px;
        }

        .timeline-location {
            color: #6c757d;
            font-size: 0.8em;
            margin-bottom: 0;
        }

        .card {
            box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
            border: 1px solid rgba(0, 0, 0, 0.125);
        }

        .card-header {
            background-color: #f8f9fa;
            border-bottom: 1px solid rgba(0, 0, 0, 0.125);
        }

        .alert-heading {
            font-size: 1.1rem;
            margin-bottom: 0.5rem;
        }

        .timeline-subtitle i {
            margin-right: 0.25rem;
        }

        .timeline-location {
            color: #6c757d;
            font-size: 0.8em;
            margin-bottom: 0;
        }

        .timeline-location i {
            margin-right: 0.25rem;
        }

        .badge {
            font-size: 0.8em;
        }

        .text-info {
            color: #17a2b8 !important;
        }

        .text-secondary {
            color: #6c757d !important;
        }
    </style>
@endsection
