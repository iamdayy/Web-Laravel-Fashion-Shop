@if ($items->isEmpty())
    <div class="m-2">
        <div class="card-body">
            <h5 class="card-title">No items found</h5>
            <p class="card-text">Please try a different search or check back later.</p>
        </div>
    </div>
@else
    @foreach ($items as $item)
        <div class="mx-0 mt-3 col-md-2 col-6" aria-hidden="true">
            <div class="shadow-sm card border-1 border-light position-relative" role="button"
                onclick="redirectTo('/products/show/{{ $item->id }}')">
                <img src="{{ asset($item->photo) }}" height="200px" class="card-img-top placeholder" alt="...">
                <div class="p-2 card-body placeholder-wave">
                    <p class="card-title placeholder">{{ mb_strimwidth($item->name, 0, 56, '...') }}</p>
                    <div class="">
                        <i class="bi bi-star-fill text-warning float-start placeholder me-2"></i>
                        <p class="placeholder">
                            @if (count($item->rating) < 1)
                                0
                            @else
                                {{ $item->rating[0]->average_rating }}
                            @endif
                        </p>
                    </div>
                    {{-- @if (!Auth::user()) --}}
                    {{-- <i class="bi bi-heart fs-5 me-1 float-end front placeholder " onclick="wishlistAlertFailed()"></i>
        @else
          <i class="bi bi-heart fs-5 me-1 float-end front placeholder " onclick="wishlistAlertFailed()"></i>
        @endif --}}
                    <p class="mb-1 card-price placeholder placeholder-lg w-75"> <span
                            class="">Rp</span>{{ number_format($item->price, 2, ',', '.') }}</p>
                    <p class="text-secondary card-sold placeholder placeholder-xs w-75 ">
                        @if (count($item->sold) < 1)
                            0
                        @elseif ($item->sold[0]->total_sold > 20000)
                            20.000+
                        @elseif ($item->sold[0]->total_sold > 10000)
                            10.000+
                        @elseif ($item->sold[0]->total_sold > 5000)
                            5.000+
                        @elseif ($item->sold[0]->total_sold > 2000)
                            2.000+
                        @elseif ($item->sold[0]->total_sold > 1000)
                            1.000+
                        @else
                            {{ $item->sold[0]->total_sold }}
                        @endif
                        Total Terjual
                    </p>
                </div>
            </div>
        </div>
    @endforeach

@endif
@if ($items->hasPages())
    <div class="m-2">
        <div class="card-footer">
            {{ $items->links() }}
        </div>
    </div>
@endif
