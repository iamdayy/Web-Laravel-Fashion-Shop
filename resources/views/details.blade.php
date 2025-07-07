@extends('layouts.global')
@section('content_home')
    <!-- Shop Detail Start -->
    <div class="container pb-5 mt-lg-4">
        <div class="row">
            <div class="col-lg-5 mb-30">
                <div class="bg-white swiper mySwiper placeholder-glow">
                    <div class="swiper-wrapper" role="button">
                        <div class="m-auto swiper-slide">
                            <img class="w-100 h-100" src="{{ asset($item->photo) }}" alt="Image">
                        </div>
                        <div class="m-auto swiper-slide">
                            <img class="w-100 h-100" src="{{ asset($item->photo) }}" alt="Image">
                        </div>
                        <div class="m-auto swiper-slide">
                            <img class="w-100 h-100" src="{{ asset($item->photo) }}" alt="Image">
                        </div>
                    </div>
                    <div class="swiper-button-next"></div>
                    <div class="swiper-button-prev"></div>
                </div>
            </div>

            <div class="h-auto col-lg-7">
                <div class="p-4 bg-white h-100">
                    <h3>{{ $item->name }}</h3>
                    <div class="mb-3 d-flex">
                        <div class="mr-2 text-warning">
                            @if (count($item->rating) > 1)
                                @foreach ($item->rating[0]->average_rating as $i)
                                    <i class="bi bi-star-fill"></i>
                                @endforeach
                            @else
                                <i class="bi bi-star-fill"></i>
                            @endif
                        </div>
                        <small class="pt-1 ms-1">({{ count($item->reviews) }}) Rating</small>
                    </div>
                    <h3 class="mb-4 font-weight-semi-bold">Rp{{ number_format($item->price, 2, ',', '.') }}</h3>
                    <div class="gap-2 mb-4 d-flex align-items-center">
                        <strong class="mr-2 text-dark">Category: </strong>
                        <span class="text-secondary"> {{ $item->category }}</span>
                    </div>
                    @if (Auth::check())
                        <div class="container-fluid">
                            <div class="row">
                                <div class="col">
                                    <form class="" action="/carts/add" method="POST" onsubmit="cartAlert()">
                                        {{ csrf_field() }}
                                        <input type="hidden" name="user_id" value="{{ Auth::user()->id }}">
                                        <input type="hidden" name="item_id" value="{{ $item->id }}">
                                        <div class="pt-2 mb-4 d-flex align-items-center">
                                            <div class="mr-3 input-group quantity" style="width: 130px;">
                                                <div class="input-group-btn">
                                                    <button class="btn btn-warning rounded-0 btn-minus">
                                                        <i class="fa fa-minus"></i>
                                                    </button>
                                                </div>
                                                <input type="text" class="text-center border-0 form-control bg-light"
                                                    name="quantity" value="1">
                                                <div class="input-group-btn">
                                                    <button class="btn btn-warning rounded-0 btn-plus">
                                                        <i class="fa fa-plus"></i>
                                                    </button>
                                                </div>
                                            </div>
                                            <button type="submit" class="px-3 btn btn-warning ms-3 rounded-0"><i
                                                    class="mr-1 fa fa-shopping-cart"></i> Add To Cart</button>
                                        </div>
                                    </form>
                                </div>
                                <div class="col">
                                    <form class="pt-2" action="/wishlists/add" method="POST" onsubmit="cartAlert()">
                                        {{ csrf_field() }}
                                        <input type="hidden" name="user_id" value="{{ Auth::user()->id }}">
                                        <input type="hidden" name="item_id" value="{{ $item->id }}">

                                        <button type="submit" class="px-3 btn btn-warning ms-3 rounded-0"><i
                                                class="mr-1 fa fa-heart"> Add To Wishlist</i></button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
        <div class="row mt-lg-4">
            <div class="col">
                <div class="bg-white p-xl-5">
                    <nav>
                        <div class="nav nav-tabs" id="nav-tab" role="tablist">
                            <button class="nav-link text-dark active" id="nav-home-tab" data-bs-toggle="tab"
                                data-bs-target="#nav-home" type="button" role="tab" aria-controls="nav-home"
                                aria-selected="true">Description</button>
                            {{-- <button class="nav-link text-dark" id="nav-contact-tab" data-bs-toggle="tab"
                                data-bs-target="#nav-contact" type="button" role="tab" aria-controls="nav-contact"
                                aria-selected="false">Reviews</button> --}}
                        </div>
                    </nav>
                    <div class="tab-content" id="nav-tabContent">
                        <div class="px-2 py-4 tab-pane fade show active" id="nav-home" role="tabpanel"
                            aria-labelledby="nav-home-tab" tabindex="0">
                            <h4 class="mb-3">Product Description</h4>
                            <p>{{ $item->description }}</p>
                        </div>
                        {{-- <div class="tab-pane fade py-xl-4 px-xl-2" id="nav-contact" role="tabpanel"
                            aria-labelledby="nav-contact-tab" tabindex="0">
                            <div class="row">
                                <div class="col-md-6">
                                    <h6 class="mb-4">{{ count($item->reviews) }} review for {{ $item->name }}</h6>
                                    @foreach ($item->reviews as $review)
                                        <div class="mb-4 media">
                                            <img src="/storage/images/person-dummy.jpg" alt="Image"
                                                class="mt-1 mr-3 img-fluid" style="width: 45px;">
                                            <div class="media-body">
                                                <h6>{{ $review->user->name }}<small> -
                                                        <i>{{ $review->created_at }}</i></small>
                                                </h6>
                                                <div class="mb-2 text-warning">
                                                    @for ($i = 0; $i < $review->rating; $i++)
                                                        <i class="fas fa-star"></i>
                                                    @endfor
                                                    @for ($j = $review->rating; $j < 5; $j++)
                                                        <i class="far fa-star"></i>
                                                    @endfor
                                                </div>
                                                <p>{{ $review->comment }}</p>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                                <div class="col-md-6">
                                    <h4 class="mb-4">Leave a review</h4>
                                    <small>Your email address will not be published. Required fields are marked *</small>
                                    <div class="my-3 d-flex">
                                        <p class="mb-0 mr-2">Your Rating * :</p>
                                        <div class="text-warning">
                                            <i class="far fa-star"></i>
                                            <i class="far fa-star"></i>
                                            <i class="far fa-star"></i>
                                            <i class="far fa-star"></i>
                                            <i class="far fa-star"></i>
                                        </div>
                                    </div>
                                    <form>
                                        <div class="form-group">
                                            <label for="message">Your Review *</label>
                                            <textarea id="message" cols="30" rows="5" class="form-control"></textarea>
                                        </div>
                                        <div class="form-group">
                                            <label for="name">Your Name *</label>
                                            <input type="text" class="form-control" id="name">
                                        </div>
                                        <div class="form-group">
                                            <label for="email">Your Email *</label>
                                            <input type="email" class="form-control" id="email">
                                        </div>
                                        <div class="mt-3 mb-0 form-group float-end">
                                            <input type="submit" value="Leave Your Review"
                                                class="px-3 btn btn-warning rounded-0">
                                        </div>
                                        <div class="clear"></div>
                                    </form>
                                </div>
                            </div>
                        </div> --}}
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Shop Detail End -->


    <!-- Products Start -->
    <div class="container py-5">
        <h2 class="text-uppercase fs-5 text-secondary">You May Also Like</h2>
        <hr width="70px">
        <div class="row">
            @foreach ($relatedItems as $rItem)
                <div class="col-lg-3">
                    <div class="bg-white product-item">
                        <div class="overflow-hidden product-img position-relative">
                            <img class="w-100 h-100" src="{{ asset($rItem->photo) }}" alt="Image">
                        </div>
                        <div class="py-4 text-center">
                            <a class="text-black h6 text-decoration-none text-truncate"
                                href="/products/show/{{ $rItem->id }}">{{ $rItem->name }}</a>
                            <div class="mt-2 d-flex align-items-center justify-content-center">
                                <h5>Rp{{ number_format($rItem->price, 2, ',', '.') }}</h5>
                            </div>
                            <div class="mb-1 d-flex align-items-center justify-content-center">
                                @if (count($rItem->rating) > 1)
                                    @foreach ($rItem->rating[0]->average_rating as $i)
                                        <i class="bi bi-star-fill text-warning"></i>
                                    @endforeach
                                @else
                                    <i class="bi bi-star-fill"></i>
                                @endif
                                <small>
                                    @if (count($rItem->rating) < 1)
                                        0
                                    @else
                                        {{ $rItem->rating[0]->average_rating }}
                                    @endif
                                </small>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
    <!-- Products End -->


    <!-- Back to Top -->
    <a href="#" class="btn btn-warning back-to-top rounded-0 "><i class="fa fa-angle-double-up"></i></a>
@endsection
