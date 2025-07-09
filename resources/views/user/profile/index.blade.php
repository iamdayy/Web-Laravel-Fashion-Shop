@extends('user.layout')
@section('content.user')
    <main class="container mt-5">
        <div class="row">
            <div class="m-auto mt-2 text-center col-md-12">
                <img src="{{ Auth::user()->photo }}" alt="" width="200px"
                    class="rounded-circle img-thumbnail border-primary">
                <h3 class="h3-responsive">{{ '@' . Auth::user()->username }}</h3>
                <p class="mt-2 fs-4">{{ Auth::user()->name }}</p>
            </div>
        </div>

        <div class="container pt-5 mb-5 text-justify">
            <h2 class="text-uppercase fs-5 text-secondary">Purchased Products</h2>
            <hr width="70px">
            <div class="row">
                @foreach ($orders as $order)
                    <div class="mx-0 mt-3 col-md-2 col-6" aria-hidden="true">
                        @foreach ($order->items as $item)
                            <div class="card">
                                <img src="{{ $item->photo }}" class="card-img-top" alt="{{ $item->name }}">
                                <div class="card-body">
                                    <h5 class="card-title">{{ $item->name }}</h5>
                                    <p class="card-text">Rp{{ number_format($item->price, 2, ',', '.') }}</p>
                                    <p class="card-text">Quantity: {{ $item->pivot->quantity }}</p>
                                    <p class="card-text">Total:
                                        Rp{{ number_format($item->price * $item->pivot->quantity, 2, ',', '.') }}</p>
                                    <p class="card-text">Status:
                                        @if ($order->status == 'selesai')
                                            <span class="text-success">{{ $order->status }}</span>
                                        @elseif ($order->status == 'pending')
                                            <span class="text-dark">{{ $order->status }}</span>
                                        @else
                                            <span class="text-danger">{{ $order->status }}</span>
                                        @endif
                                    </p>
                                    <p class="card-text">Purchased at: {{ $order->created_at }}</p <a
                                        href="{{ route('orders.show', $order->id) }}" class="btn btn-primary btn-sm">
                                    Detail</a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endforeach
            </div>
        </div>
    </main>
@endsection
