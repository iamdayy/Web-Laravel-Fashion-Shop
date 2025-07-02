<div id="dashboard">
    <h2>Items</h2>
    <div class="table-responsive">
        <table class="table table-striped table-sm">
            <thead>
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">ID</th>
                    <th scope="col">Name</th>
                    <th scope="col">Description</th>
                    <th scope="col">Rating</th>
                    <th scope="col">Stock</th>
                    <th scope="col">Price</th>
                    <th scope="col">Sold</th>
                    <th scope="col">Photo</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($items as $item)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $item->id }}</td>
                        <td>{{ $item->name }}</td>
                        <td>{{ $item->description }}</td>
                        <td>
                            @if (count($item->rating) < 1)
                                0
                            @else
                                @foreach ($item->rating[0]->average_rating as $i)
                                    <i class="bi bi-star-fill text-warning"></i>
                                @endforeach
                            @endif
                        </td>
                        <td>{{ $item->stock }}</td>
                        <td>Rp{{ number_format($item->price, 2, ',', '.') }}</td>
                        <td>
                            @if (count($item->sold) < 1)
                                0
                            @elseif ($item->sold[0]->total_sold == 0)
                                0
                            @else
                                {{ $item->sold[0]->total_sold }}
                            @endif
                        </td>
                        <td>
                            <img src="{{ $item->photo }}" width="100px" alt="{{ $item->name }}" />
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
