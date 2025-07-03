<div class="row">
    @foreach ($offers as $offer)
        <div class="col-md-6">
            <div class="product-offer mb-30" style="height: 300px;">
                <img class="img-fluid" src="{{ asset($offer->image) }}" alt="">
                <div class="offer-text">
                    <h6 class="text-white text-uppercase">Save {{ $offer->discount_percentage }}%</h6>
                    <h3 class="mb-3 text-white">{{ $offer->title }}</h3>
                    <p class="text-white text-truncate">{{ $offer->description }}</p>
                    <a href="{{ $offer->link }}" class="btn btn-warning rounded-0">
                        Shop Now
                    </a>
                </div>
            </div>
        </div>
    @endforeach
</div>
