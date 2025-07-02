@extends('user.layout')
@section('content.user')
    <div class="container">
        <main class="">
            <!-- Breadcrumb Start -->
            <div class="container mt-4">
                <div class="row">
                    <div class="col-12">
                        <nav class="px-3 py-3 mb-5 bg-white breadcrumb">
                            <a class="breadcrumb-item text-dark" href="#">Home</a>
                            <a class="breadcrumb-item text-dark" href="#">Products</a>
                            <span class="breadcrumb-item active">Shopping Cart</span>
                        </nav>
                    </div>
                </div>
            </div>
            <!-- Breadcrumb End -->


            <!-- Cart Start -->
            <div class="container-fluid">
                <div class="row px-xl-5">
                    <div class="mb-5 col-lg-8 table-responsive">
                        <table class="table mb-0 text-center table-light table-borderless table-hover">
                            <thead class="table-dark">
                                <tr>
                                    <th>Products</th>
                                    <th>Price</th>
                                    <th>Quantity</th>
                                    <th>Total</th>
                                    <th>Remove</th>
                                </tr>
                            </thead>
                            <tbody class="align-middle">
                                @foreach ($carts as $cart)
                                    <tr>
                                        <td class="align-middle"><img src="{{ $cart->item->photo }}" alt=""
                                                style="width: 50px;">
                                            <a href="/products/show/{{ $cart->item_id }}"
                                                class="text-decoration-none text-secondary">{{ mb_strimwidth($cart->item->name, 0, 30, '...') }}</a>
                                        </td>
                                        <td class="align-middle">Rp{{ number_format($cart->item->price, 2, ',', '.') }}</td>
                                        <td class="m-auto align-middle">
                                            <div class="d-flex align-items-center justify-content-center">
                                                <form action="/carts/update" method="post">
                                                    {{ csrf_field() }}
                                                    <input type="hidden" name="item_id" value="{{ $cart->item_id }}">
                                                    <div class="input-group quantity" style="width: 130px;">
                                                        <div class="input-group-btn">
                                                            <button type="submit" value='-1' name="minus"
                                                                class="btn btn-warning rounded-0 btn-minus">
                                                                <i class="fa fa-minus"></i>
                                                            </button>
                                                        </div>
                                                        <input type="number" name="quantity"
                                                            class="text-center border-0 form-control bg-light"
                                                            value="{{ $cart->quantity }}">
                                                        <div class="input-group-btn">
                                                            <button type="submit" value='1'
                                                                class="btn btn-warning rounded-0 btn-plus" name="plus">
                                                                <i class="fa fa-plus"></i>
                                                            </button>
                                                        </div>
                                                    </div>
                                                </form>
                                        </td>
                                        <td class="align-middle">
                                            Rp{{ number_format($cart->item->price * $cart->quantity, 2, ',', '.') }}</td>
                                        <td class="align-middle"><button class="btn btn-sm btn-danger rounded-0"><i
                                                    class="fa fa-times"></i></button></td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="col-lg-4">
                        <form class="mb-5" action="">
                            <div class="input-group">
                                <input type="text" class="border-0 form-control rounded-0" placeholder="Coupon Code">
                                <div class="input-group-append">
                                    <button class="btn btn-warning rounded-0">Apply Coupon</button>
                                </div>
                            </div>
                        </form>
                        <h5 class="mb-3 section-title position-relative text-uppercase"><span class="bg-light pe-3">Cart
                                Summary</span></h5>
                        <div class="mb-5 bg-light p-30">
                            <div class="pb-2 border-bottom">
                                <div class="mb-3 d-flex justify-content-between">
                                    <h6>Subtotal</h6>
                                    <h6>Rp{{ number_format($subtotal, 2, ',', '.') }}</h6>
                                </div>
                            </div>
                            <div class="pt-2">
                                <div class="mt-2 d-flex justify-content-between">
                                    <h5>Total</h5>
                                    <h5>Rp{{ number_format($subtotal, 2, ',', '.') }}</h5>
                                </div>
                                <form action="/carts/checkout" method="post" onsubmit="cartAlert()">
                                    {{ csrf_field() }}
                                    <button class="py-3 my-3 btn btn-block btn-warning rounded-0 font-weight-bold">Proceed
                                        To Checkout</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Back to Top -->
            <a href="#" class="btn btn-primary back-to-top"><i class="fa fa-angle-double-up"></i></a>
        </main>
    </div>
    </div>
@endsection
