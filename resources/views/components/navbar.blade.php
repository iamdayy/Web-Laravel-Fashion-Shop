<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container">
        <div id="logo bg-dark">
            <a class="text-white navbar-brand text-upper brand" href="/">
                {{ config('app.name') }}
                <span class="text-danger">.</span></a>
        </div>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarScroll"
            aria-controls="navbarScroll" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarScroll">
            <ul class="my-2 navbar-nav my-lg-0 navbar-nav-scroll" style="--bs-scroll-height: 300px;">
                <li class="nav-item">
                    <a class="nav-link" id="home" aria-current="page" href="/">Home</a>
                </li>
                <li class="my-auto nav-item me-3">
                    <div class="dropdown">
                        <a id="products" class="bg-transparent nav-link text-decoration-none dropdown-toggle"
                            href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            Products
                        </a>

                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="/products">All Products</a></li>
                            <li><a class="dropdown-item" href="/products/new-arrivals">New Arrivals</a></li>
                            <li><a class="dropdown-item" href="/products/sale">Sale</a></li>
                        </ul>
                    </div>
                </li>
                <li class="my-auto nav-item me-3">
                    <div class="dropdown">
                        <a id="other" class="bg-transparent nav-link text-decoration-none dropdown-toggle"
                            href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            Other
                        </a>

                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="/products/accessories">Accessories</a></li>
                            <li><a class="dropdown-item" href="/products/shoes">Shoes</a></li>
                            <li><a href="/products/women" class="dropdown-item">Women</a></li>
                            <li><a href="/products/men" class="dropdown-item">Men</a></li>
                            <li><a href="/products/children" class="dropdown-item">Children</a></li>
                            <li><a href="/products/parfume" class="dropdown-item">Parfume</a></li>
                        </ul>
                    </div>
                </li>
            </ul>
            <form class="d-inline w-100 ms-3 me-3" role="/search">
                <input class="form-control w-100 rounded-0" type="search" placeholder="Search something..."
                    aria-label="Search">
            </form>
            <ul class="my-2 navbar-nav my-lg-0 navbar-nav-scroll" style="--bs-scroll-height: 300px;">
                @if (!Auth::user())
                    <li class="nav-item">
                        <button class="btn btn-link text-decoration-underline nav-link w-100" data-bs-toggle="modal"
                            data-bs-target="#loginModal">
                            Login
                        </button>
                    </li>
                @else
                    <li class="my-auto text-center nav-item me-2">
                        <a id="wishlist" class="nav-link" aria-current="page" href="/wishlist">
                            <i class="bi bi-heart fs-5"></i><span>Wishlist</span>
                        </a>
                    </li>
                    <li class="text-center nav-item me-2">
                        <a id="cart" class="nav-link" href="/cart">
                            <i class="bi bi-cart3 fs-5"></i><span>Cart</span>
                        </a>
                    </li>
                    <li class="text-center nav-item me-2 me-3">
                        <a id="orders" class="nav-link" href="/orders">
                            <i class="bi bi-truck fs-5"></i><span>Orders</span>
                        </a>
                    </li>
                    <li class="my-auto nav-item">
                        <div class="dropdown fs-5">
                            <a class="bg-transparent text-secondary text-decoration-none dropdown-toggle" href="#"
                                role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <img src="{{ Auth::user()->photo }}" width="35px" class="rounded-circle "
                                    alt="">
                            </a>

                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item">Hi, {{ Auth::user()->name }} </a></li>
                                <li><a class="dropdown-item" href="/profile"> <i
                                            class="bi bi-person-circle me-2"></i>
                                        Profil</a></li>
                                <li><a class="dropdown-item" href="/setting"> <i class="bi bi-gear me-2"></i>
                                        Setting</a></li>
                                <li><a class="dropdown-item" href="/auth/logout"> <i
                                            class="bi bi-box-arrow-left me-2"></i> Logout</a></li>
                            </ul>
                        </div>
                    </li>
                @endif
            </ul>
        </div>
    </div>
</nav>
@include('components.login_modal')
@include('components.register_modal')
