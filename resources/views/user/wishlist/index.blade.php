@extends('user.layout')
@section('content.user')
    <main class="container">
        <!-- Breadcrumb Start -->
        <div class="mt-4 row">
            <div class="col-12">
                <nav class="px-3 py-3 mb-4 breadcrumb">
                    <a class="breadcrumb-item text-dark" href="#">Home</a>
                    <a class="breadcrumb-item text-dark" href="#">Products</a>
                    <span class="breadcrumb-item active">Wishlist</span>
                </nav>
            </div>
        </div>
        <!-- Breadcrumb End -->
        <!-- wl Start -->
        <div class="container mb-5 text-justify">
            <h2 class="text-uppercase fs-5 text-secondary">Your Wishlist</h2>
            <hr width="70px">
            <div class="row justify-content-sm-center">
                {{-- @php
              sizeof($wishlists);
              exit;
            @endphp --}}
                {{-- @if (sizeof($wishlists)) --}}
                @foreach ($wishlists as $wl)
                    <div class="mx-0 mt-3 col-md-2 col-6" aria-hidden="true">
                        <div class="shadow-sm card border-1 border-light position-relative" role="button"
                            onclick="redirectTo('/products/show/{{ $wl->item->id }}')">
                            <img src="{{ $wl->item->photo }}" height="200px" class="card-img-top placeholder"
                                alt="...">
                            <div class="p-2 card-body placeholder-wave">
                                <p class="placeholder">{{ mb_strimwidth($wl->item->name, 0, 40, '...') }}</p>

                                <div class="">

                                    <div class="mb-1 d-flex align-items-center justify-content-center">
                                        @if (count($wl->item->rating) > 1)
                                            @foreach ($wl->item->rating[0]->average_rating as $i)
                                                <i class="bi bi-star-fill text-warning"></i>
                                            @endforeach
                                        @else
                                            <i class="bi bi-star-fill"></i>
                                        @endif
                                        <small>
                                            @if (count($wl->item->rating) < 1)
                                                0
                                            @else
                                                {{ $wl->item->rating[0]->average_rating }}
                                            @endif
                                        </small>
                                    </div>
                                </div>

                                <p class="mb-1 card-price placeholder placeholder-lg w-75"> <span
                                        class="">Rp</span>{{ number_format($wl->item->price, 2, ',', '.') }}</p>
                                <p class="text-secondary card-sold placeholder placeholder-xs w-75 ">
                                    @if (count($wl->item->sold) < 1)
                                        0
                                    @elseif ($wl->item->sold[0]->total_sold > 20000)
                                        20.000+
                                    @elseif ($wl->item->sold[0]->total_sold > 10000)
                                        10.000+
                                    @elseif ($wl->item->sold[0]->total_sold > 5000)
                                        5.000+
                                    @elseif ($wl->item->sold[0]->total_sold > 2000)
                                        2.000+
                                    @elseif ($wl->item->sold[0]->total_sold > 1000)
                                        1.000+
                                    @else
                                        {{ $wl->item->sold[0]->total_sold }}
                                    @endif
                                    Total Terjual
                                </p>
                            </div>
                            <div class="text-center row">
                                <td>
                                    <form action="{{ route('delete', $wl->id) }}" method="post" style="display inline"
                                        onsubmit='deleteAlert("/wishlists/delete/{{ $wl->id }}")'>
                                        @csrf
                                        @method('delete')
                                        <button type="submit" class="mb-3 btn btn-danger">Remove</button>
                                    </form>
                                </td>
                            </div>
                        </div>
                    </div>
                @endforeach
                {{-- @endif --}}
                <!-- <div class="mt-3 mb-3 w-100 text-end me-2">
                        <a href="/products" class="text-secondary">Lihat selengkapnya...</a>
                      </div>
                    </div>
                  </div> -->

                <!-- Back to Top -->
                <a href="#" class="btn btn-primary back-to-top"><i class="fa fa-angle-double-up"></i></a>
    </main>
@endsection
