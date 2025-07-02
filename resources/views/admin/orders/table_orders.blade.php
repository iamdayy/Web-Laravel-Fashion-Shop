<h4>Orders</h4>
<div class="table-responsive">
    <table class="table table-striped table-sm">
        <thead class="">
            <tr>
                <th scope="col">Item @Qty</th>
                <th scope="col">User</th>
                <th scope="col">Total Price</th>
                <th scope="col">Date Transaction</th>
                <th scope="col">Update at</th>
                <th scope="col">Status</th>
                <th></th>
            </tr>
        </thead>
        <tbody class="">
            @foreach ($orders as $order)
                <tr>
                    <td class="py-3">
                        @php
                            $totalPrice = 0;
                        @endphp
                        @foreach ($order->items as $item)
                            <div class="w-full px-2 justify-content-between d-flex align-items-center">
                                <div class="d-flex align-items-center">
                                    <img src="{{ $item->photo }}" alt="{{ $item->name }}" class="rounded-circle me-2"
                                        width="50">
                                    <span>{{ $item->name }}</span>
                                </div>
                                <span class="badge bg-secondary ms-2">{{ $item->pivot->quantity }}</span>
                                @php
                                    $totalPrice += $item->price * $item->pivot->quantity;
                                @endphp
                            </div>
                        @endforeach
                    </td>
                    <td class="py-3">
                        @if ($order->user)
                            {{ $order->user->name }}
                        @else
                            <span class="text-muted">Unknown User</span>
                        @endif
                    </td>
                    <td class="py-3">Rp{{ number_format($totalPrice, 2, ',', '.') }}</td>
                    <td class="py-3">{{ $order->created_at }}</td>
                    <td class="py-3">{{ $order->updated_at }}</td>
                    @if ($order->status == 'success')
                        <th class="py-3 text-success">{{ $order->status }}</th>
                    @elseif ($order->status == 'pending')
                        <th class="py-3 text-dark">{{ $order->status }}</th>
                    @else
                        <th class="py-3 text-danger">{{ $order->status }}</th>
                    @endif
                    <td class="py-3">
                        <a href="{{ route('admin.orders.show', $order->id) }}"
                            class="btn btn-primary btn-sm">Detail</a>
                    </td>
                </tr>
            @endforeach

        </tbody>
    </table>
    @if ($orders->hasPages())
        <div class="card-footer">
            {{ $orders->links() }}
        </div>
    @endif
</div>
