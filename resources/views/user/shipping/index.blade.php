{{--
    This file is part of the Laravel Fashion Shop project.
    It is used to display the payment page for a specific order.
    The page includes the order details and a form to process the payment. --}}
@extends('user.layout')
@section('content.user')
    <div class="container mt-5">
        <h2 class="text-uppercase fs-5 text-secondary">Shipping for Order #{{ $order->id }}</h2>
        <hr width="70px">

        <div class="row">
            <div class="col-md-6">
                <h4>Order Details</h4>
                <p><strong>Order ID:</strong> {{ $order->id }}</p>
                <p><strong>Total Amount:</strong> ${{ number_format($order->total_amount, 2) }}</p>
                <p><strong>Status:</strong> {{ $order->status }}</p>
            </div>

            <!-- Shipping Form Section -->
            <div class="col-md-6">
                <h4>Shipping Form</h4>
                <form id="shippingForm" action="/shipping/create/{{ $order->id }}" method="post"
                    enctype="multipart/form-data">
                    {{ csrf_field() }}
                    <!-- Subdistrict/Village -->
                    <div class="mb-3">
                        <label for="subdistrict" class="form-label">Desa/Kelurahan</label>
                        <div class="search-select-container">
                            <div class="dropdown w-100">
                                <button
                                    class="btn btn-outline-secondary dropdown-toggle w-100 text-start d-flex justify-content-between align-items-center"
                                    type="button" id="subdistrictButton" data-bs-toggle="dropdown" aria-expanded="false">
                                    <span id="subdistrictText">Pilih Desa/Kelurahan...</span>
                                    <i class="bi bi-chevron-down"></i>
                                </button>
                                <div class="p-0 dropdown-menu w-100" aria-labelledby="subdistrictButton">
                                    <div class="p-2 border-bottom">
                                        <input type="text" class="form-control" id="subdistrictSearch"
                                            placeholder="Cari desa/kelurahan..." autocomplete="off">
                                    </div>
                                    <div class="dropdown-options" id="subdistrictOptions"
                                        style="max-height: 200px; overflow-y: auto;">
                                        <!-- Options will be loaded here -->
                                    </div>
                                    <div class="p-2 text-center text-muted d-none" id="subdistrictNoResults">
                                        <small>Tidak ada hasil ditemukan</small>
                                    </div>
                                    <div class="p-2 text-center d-none" id="subdistrictLoading">
                                        <div class="spinner-border loading-spinner" role="status">
                                            <span class="visually-hidden">Loading...</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <input type="hidden" id="subdistrictValue">
                            <input type="hidden" id="subdistrict" name="subdistrict">
                        </div>
                    </div>

                    <!-- District -->
                    <div class="mb-3">
                        <label for="district" class="form-label">Kecamatan</label>
                        <input class="form-control" id="district" name="district" readonly>
                    </div>

                    <!-- City -->
                    <div class="mb-3">
                        <label for="city" class="form-label">Kota/Kabupaten</label>
                        <input class="form-control" id="city" name="city" readonly>
                    </div>

                    <!-- State/Province -->
                    <div class="mb-3">
                        <label for="state" class="form-label">Provinsi</label>
                        <input class="form-control" id="state" name="state" readonly>
                    </div>

                    <!-- Postal Code -->
                    <div class="mb-3">
                        <label for="postal_code" class="form-label">Kode Pos</label>
                        <input type="text" class="form-control" id="postal_code" name="postal_code" readonly>
                    </div>

                    <!-- Full Address -->
                    <div class="mb-3">
                        <label for="address" class="form-label">Alamat Lengkap</label>
                        <textarea class="form-control" id="address" name="address" rows="3" required></textarea>
                    </div>

                    <!-- Phone Number -->
                    <div class="mb-3">
                        <label for="phone" class="form-label">Phone Number</label>
                        <input type="text" class="form-control" id="phone" name="phone" required>
                    </div>

                    <!-- Courier Selection -->
                    <div class="mb-3">
                        <label for="courier" class="form-label">Courier</label>
                        <select class="form-select" id="courier" name="courier" required>
                            <option value="">Pilih Kurir</option>
                            <option value="jne">JNE</option>
                            <option value="tiki">TIKI</option>
                            <option value="pos">POS Indonesia</option>
                            <option value="jnt">J&T Express</option>
                            <option value="sicepat">SiCepat</option>
                            <option value="anteraja">Anteraja</option>
                        </select>
                    </div>

                    <!-- Weight Input (for calculation) -->
                    <div class="mb-3">
                        <label for="weight" class="form-label">Berat Paket (gram)</label>
                        <input type="number" class="form-control" id="weight" name="weight" value="1000"
                            min="1" required>
                        <div class="form-text">Estimasi berat berdasarkan item yang dipesan</div>
                    </div>

                    <!-- Calculate Shipping Button -->
                    <div class="mb-3">
                        <button type="button" class="btn btn-info w-100" id="calculateShippingBtn">
                            <i class="bi bi-calculator"></i> Hitung Ongkos Kirim
                        </button>
                    </div>

                    <!-- Shipping Cost Selection -->
                    <div class="mb-3">
                        <label for="shipping_cost" class="form-label">Shipping Cost</label>
                        <select class="form-select" id="shipping_cost" name="shipping_cost" required>
                        </select>
                    </div>

                    <button type="submit" class="btn btn-primary w-100">Submit Shipping Details</button>
                </form>
            </div>
        </div>

        <!-- Loading Section -->
        <div id="loadingSection" class="mt-4 text-center d-none">
            <div class="spinner-border text-primary" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
            <p class="mt-2">Menghitung ongkos kirim...</p>
        </div>
        <div class="mt-4">
            <h4>Order Items</h4>
            <ul class="list-group ">
                @foreach ($order->items as $item)
                    <li class="list-group -item">
                        <div class="gap-2 d-flex align-items-center">
                            <strong>{{ $item->name }}</strong>
                            <span class="badge bg-secondary float-end">Quantity: {{ $item->pivot->quantity }}</span>
                        </div>
                        <span class="float-end me-2">Price: Rp{{ number_format($item->price, 2, ',', '.') }}</span>
                    </li>
                @endforeach
            </ul>
        </div>
        <div class="mt-4">
            <a href="/orders" class="btn btn-secondary">Back to Orders</a>
        </div>
    </div>
@endsection

<script>
    // Debounce function
    function debounce(func, wait) {
        let timeout;
        return function executedFunction(...args) {
            const later = () => {
                clearTimeout(timeout);
                func(...args);
            };
            clearTimeout(timeout);
            timeout = setTimeout(later, wait);
        };
    }

    // Search locations function
    async function searchLocations(query) {
        console.log('Searching locations for query:', query);
        if (query.length < 2) return [];

        try {
            const response = await fetch(`/rajaongkir/destination?query=${encodeURIComponent(query)}`, {
                method: 'GET',
                headers: {
                    'Content-Type': 'application/json',
                    'key': '{{ config('rajaongkir_api.key') }}'
                }
            });
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            const data = await response.json();
            if (!data || !Array.isArray(data)) {
                console.error('Invalid data format:', data);
                return [];
            }
            return data.map(location => ({
                id: location.id,
                subdistrict: location.subdistrict_name,
                district: location.district_name,
                city: location.city_name,
                province: location.province_name,
                postal_code: location.zip_code
            }));
        } catch (error) {
            console.error('Error searching locations:', error);
            return [];
        }
    }

    // Initialize subdistrict search
    function initializeSubdistrictSearch() {
        const searchInput = document.getElementById('subdistrictSearch');
        const optionsContainer = document.getElementById('subdistrictOptions');
        const selectedText = document.getElementById('subdistrictText');
        const selectedValue = document.getElementById('subdistrictValue');
        const noResults = document.getElementById('subdistrictNoResults');
        const button = document.getElementById('subdistrictButton');
        const loading = document.getElementById('subdistrictLoading');

        const debouncedSearch = debounce(async (query) => {
            if (query.length < 2) {
                optionsContainer.innerHTML = '';
                noResults.classList.add('d-none');
                return;
            }

            loading.classList.remove('d-none');
            const locations = await searchLocations(query);
            loading.classList.add('d-none');

            optionsContainer.innerHTML = '';

            if (locations.length === 0) {
                noResults.classList.remove('d-none');
            } else {
                noResults.classList.add('d-none');
                locations.forEach(location => {
                    const option = document.createElement('a');
                    option.className = 'dropdown-item';
                    option.href = '#';
                    option.innerHTML = `
                            <div>
                                <strong>${location.subdistrict}</strong><br>
                                <small class="text-muted">${location.district}, ${location.city}, ${location.province}</small>
                            </div>
                        `;

                    option.addEventListener('click', (e) => {
                        e.preventDefault();

                        // Update all form fields
                        selectedText.textContent = location.subdistrict;
                        selectedValue.value = location.id;
                        subdistrictValue.value = location.id;
                        document.getElementById('subdistrict').value = location.subdistrict;
                        document.getElementById('district').value = location.district;
                        document.getElementById('city').value = location.city;
                        document.getElementById('state').value = location.province;
                        document.getElementById('postal_code').value = location.postal_code;

                        const dropdown = bootstrap.Dropdown.getInstance(button);
                        if (dropdown) {
                            dropdown.hide();
                        }
                    });

                    optionsContainer.appendChild(option);
                });
            }
        }, 300);

        searchInput.addEventListener('input', (e) => {
            debouncedSearch(e.target.value);
        });

        searchInput.addEventListener('click', (e) => {
            e.stopPropagation();
        });

        button.addEventListener('shown.bs.dropdown', () => {
            searchInput.focus();
        });

        button.addEventListener('hidden.bs.dropdown', () => {
            searchInput.value = '';
            optionsContainer.innerHTML = '';
            noResults.classList.add('d-none');
        });
    }

    // Calculate shipping cost
    async function calculateShippingCost() {
        const destinationId = document.getElementById('subdistrictValue').value;
        const weight = document.getElementById('weight').value;
        const courier = document.getElementById('courier').value;

        if (!destinationId || !weight || !courier) {
            alert('Mohon lengkapi alamat tujuan, berat paket, dan pilih kurir terlebih dahulu.');
            return;
        }

        const loadingSection = document.getElementById('loadingSection');
        const shippingCostSelect = document.getElementById('shipping_cost');

        try {
            loadingSection.classList.remove('d-none');

            $response = await fetch(
                `/rajaongkir/cost?destination=${destinationId}&weight=${weight}&courier=${courier}`, {
                    method: 'GET',
                });
            if (!$response.ok) {
                throw new Error('Network response was not ok');
            }
            const data = await $response.json();
            if (!data || !data || data.length === 0) {
                alert('Tidak ada opsi pengiriman yang tersedia untuk alamat ini.');
                return;
            }
            const shippingOptions = data.map(option => ({
                service: option.service,
                cost: option.cost,
                etd: option.etd
            }));

            // Display results
            displayShippingResults(shippingOptions, courier.toUpperCase());

        } catch (error) {
            console.error('Error calculating shipping cost:', error);
            alert('Terjadi kesalahan saat menghitung ongkos kirim.');
        } finally {
            loadingSection.classList.add('d-none');
        }
    }

    // Display shipping results
    function displayShippingResults(options, courierName) {
        const shippingSelect = document.getElementById('shipping_cost');
        shippingSelect.innerHTML = '<option value="">Pilih Opsi Pengiriman</option>';
        options.forEach(option => {
            const opt = document.createElement('option');
            opt.value = option.cost;
            opt.textContent =
                `${courierName} - ${option.service} - Rp${option.cost.toLocaleString()} (${option.etd})`;
            shippingSelect.appendChild(opt);
        });
    }

    // Initialize the application
    document.addEventListener('DOMContentLoaded', () => {
        initializeSubdistrictSearch();

        // Calculate shipping button event
        document.getElementById('calculateShippingBtn').addEventListener('click', calculateShippingCost);
    });
</script>
