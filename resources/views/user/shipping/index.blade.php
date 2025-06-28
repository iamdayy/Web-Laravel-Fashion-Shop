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
            <div class="col-md-6">
                <h4>Shipping Form</h4>
                <form action="/shipping/create/{{ $order->id }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label for="state" class="form-label">Provinsi</label>
                        <select class="form-select" id="state" name="state" required>
                            <option value="">Pilih Provinsi</option>

                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="city" class="form-label">Kota/Kabupaten</label>
                        <select class="form-select" id="city" name="city" required>
                            <option value="">Pilih Kota/Kabupaten</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="district" class="form-label">Kecamatan</label>
                        <input class="form-control" id="district" name="district" required>
                    </div>
                    <div class="mb-3">
                        <label for="subdistrict" class="form-label">Desa/Kelurahan</label>
                        <input class="form-control" list="subdistrictOptions" id="subdistrict">
                    </div>
                    <div class="mb-3">
                        <label for="postal_code" class="form-label">Kode Pos</label>
                        <input type="text" class="form-control" id="postal_code" name="postal_code" required>
                    </div>
                    <div class="mb-3">
                        <label for="address" class="form-label">Alamat Lengkap</label>
                        <textarea class="form-control" id="address" name="address" required>
                        </textarea>
                    </div>
                    <div class="mb-3">
                        <label for="phone" class="form-label">Phone Number</label>
                        <input type="text" class="form-control" id="phone" name="phone" required>
                    </div>
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
                    <div class="mb-3">
                        <label for="shipping_cost" class="form-label">Shipping Cost</label>
                        <select class="form-select" id="shipping_cost" name="shipping_cost" required>
                            <option>Select Shiping Cost</option>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary">Submit Shipping Details</button>
                </form>
            </div>
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

<script type="text/javascript">
    document.addEventListener('DOMContentLoaded', function() {
        const stateSelect = document.getElementById('state');
        const citySelect = document.getElementById('city');
        const postalCodeInput = document.getElementById('postal_code');
        const courierSelect = document.getElementById('courier');

        // Fetch provinces from the API
        fetch('/api/rajaongkir/provincies')
            .then(response => response.json())
            .then(data => {
                data.forEach(province => {
                    const option = document.createElement('option');
                    option.value = province.province_id;
                    option.textContent = province.province;
                    stateSelect.appendChild(option);
                });
            })
            .catch(error => {
                console.error('Error fetching provinces:', error);
            });

        // Fetch cities when a province is selected
        stateSelect.addEventListener('change', function() {
            const provinceId = this.value;
            if (provinceId) {
                fetch(`/api/rajaongkir/provincies/${provinceId}`)
                    .then(response => response.json())
                    .then(data => {
                        data.forEach(city => {
                            const option = document.createElement('option');
                            option.value = city.city_id;
                            option.textContent = city.city_name;
                            citySelect.appendChild(option);
                        });
                        // Clear previous subdistrict options
                        const subdistrictOptions = document.getElementById('subdistrictOptions');
                        subdistrictOptions.innerHTML = '';
                    })
                    .catch(error => {
                        console.error('Error fetching cities:', error);
                    });
            } else {
                cityInput.value = ''; // Clear the city input if no province is selected
                const subdistrictOptions = document.getElementById('subdistrictOptions');
                subdistrictOptions.innerHTML = ''; // Clear previous options
            }
        });
        // citySelect.addEventListener('change', function() {
        //     const cityId = this.value;
        //     if (cityId) {
        //         // fetch(`/api/rajaongkir/cities/${cityId}`)
        //         //     .then(response => response.json())
        //         //     .then(data => {
        //         //         const subdistrictOptions = document.getElementById('subdistrictOptions');
        //         //         subdistrictOptions.innerHTML = ''; // Clear previous options
        //         //         data.forEach(subdistrict => {
        //         //             const option = document.createElement('option');
        //         //             option.value = subdistrict.subdistrict_id;
        //         //             option.textContent = subdistrict.subdistrict_name;
        //         //             subdistrictOptions.appendChild(option);
        //         //         });
        //         //     })
        //         //     .catch(error => {
        //         //         console.error('Error fetching subdistricts:', error);
        //         //     });

        //     } else {
        //         const subdistrictOptions = document.getElementById('subdistrictOptions');
        //         subdistrictOptions.innerHTML = ''; // Clear previous options
        //     }
        // });

        // Fetch shipping costs when a courier is selected
        courierSelect.addEventListener('change', function() {
            const courier = this.value;
            const cityId = citySelect.value;
            if (courier && cityId) {
                fetch('/api/rajaongkir/cost', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                        },
                        body: JSON.stringify({
                            courier: courier,
                            destination: cityId,
                            weight: 1000 // Example weight, adjust as needed
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        const shippingCostSelect = document.getElementById('shipping_cost');
                        shippingCostSelect.innerHTML = ''; // Clear previous options
                        data.forEach(cost => {
                            const option = document.createElement('option');
                            option.value = cost.cost[0].value;
                            option.textContent =
                                `${cost.service} - Rp${cost.cost[0].value.toLocaleString('id-ID')} - ${cost.cost[0].etd} Hari`;
                            shippingCostSelect.appendChild(option);
                        });
                    })
                    .catch(error => {
                        console.error('Error fetching shipping costs:', error);
                    });
            }
        });
    });
</script>
