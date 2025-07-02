<nav id="sidebarMenu" class="col-md-3 col-lg-2 d-md-block bg-dark sidebar collapse">
    <div class="pt-3 position-sticky sidebar-sticky">
        <ul class="nav flex-column">
            <div class="text-center">
                <i class="bi bi-speedometer2 text-light fs-1"></i>
                <p class="text-light">NIKKY ADMIN</p>
            </div>
            <hr class="mb-2 me-3 ms-3 text-light">
            <li class="nav-item">
                <a id="dashboard" class="nav-link text-light" aria-current="page" href="/admin/dashboard">
                    <span id="dashboard_icon" data-feather="pie-chart" class="align-text-bottom text-light"></span>
                    Dashboard
                </a>
            </li>
            <hr class="mt-2 me-3 ms-3 text-light">
            <li class="nav-item">
                <a id="products" class="nav-link text-light" href="/admin/products">
                    <span id="products_icon" data-feather="shopping-cart" class="align-text-bottom text-light"></span>
                    Products
                    {{-- <span class="badge text-bg-danger float-end">1</span> --}}
                </a>
            </li>
            <li class="nav-item">
                <a id="orders" class="nav-link text-light" href="/admin/orders">
                    <span id="orders_icon" data-feather="file" class="align-text-bottom text-light"></span>
                    Orders
                    <span class="badge text-bg-danger float-end">{{ $countPendingOrders }}</span>
                </a>
            </li>
            <li class="nav-item">
                <a id="users" class="nav-link text-light" href="/admin/users">
                    <span id="users_icon" data-feather="users" class="align-text-bottom text-light"></span>
                    Users
                    {{-- <span class="badge text-bg-danger float-end">2</span> --}}
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link text-light" href="/auth/logout">
                    <span data-feather="log-out" class="align-text-bottom text-light"></span>
                    Log Out
                </a>
            </li>
            {{-- <li class="nav-item">
        <a class="nav-link" href="#">
          <span data-feather="bar-chart-2" class="align-text-bottom"></span>
          Reports
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="#">
          <span data-feather="layers" class="align-text-bottom"></span>
          Integrations
        </a>
      </li> --}}
        </ul>
    </div>
</nav>
