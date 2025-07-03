<div class="swiper mySwiper placeholder-glow">
    <div class="swiper-wrapper" role="button">
        @php
            // dd($banners); // Debugging line, remove in production
        @endphp
        @foreach ($banners as $banner)
            <div class="m-auto swiper-slide placeholder" aria-hidden="false">
                <a href="{{ $banner['redirectUrl'] }}" class="text-decoration-none">
                    <div class="placeholder-glow">
                        <img src="{{ asset($banner['imageUrl']) }}" class="img-fluid img-ads h-100" alt="..."
                            loading="preload">
                    </div>
            </div>
        @endforeach
    </div>
    <div class="swiper-button-next"></div>
    <div class="swiper-button-prev"></div>
</div>
