@extends('admin.layout')
@section('content_admin')

    <body>
        <div class="my-3 container-fluid">
            <div class="row">
                @include('admin.components.sidebar')
                <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
                    @foreach ($items as $item)
                        @if ($item->id == $id)
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th scope="col"></th>
                                        <th scope="col"></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <th scope="row">Photo</th>
                                        <td><img src="{{ $item->photo }}" width="150px" alt=""></td>
                                    </tr>
                                    <tr>
                                        <th scope="row">ID</th>
                                        <td>{{ $item->id }}</td>
                                    </tr>
                                    <tr>
                                        <th scope="row">Name</th>
                                        <td>{{ $item->name }}</td>
                                    </tr>
                                    <tr>
                                        <th scope="row">Description</th>
                                        <td>{{ $item->description }}</td>
                                    </tr>
                                    <tr>
                                        <th scope="row">Category</th>
                                        <td>{{ Str::ucfirst($item->category) }}</td>
                                    </tr>
                                    <tr>
                                        <th scope="row">Rating</th>
                                        <td>
                                            @if (count($item->rating) < 1)
                                                0
                                            @else
                                                @foreach ($item->rating[0]->average_rating as $i)
                                                    <i class="bi bi-star-fill text-warning"></i>
                                                @endforeach
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <th scope="row">Review</th>
                                        <td>
                                            @if (count($item->reviews) < 1)
                                                0
                                            @else
                                                {{ count($item->reviews) }}
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <th scope="row">Stock</th>
                                        <td>{{ $item->stock }}</td>
                                    </tr>
                                    <tr>
                                        <th scope="row">Price</th>
                                        <td>Rp</span>{{ number_format($item->price, 2, ',', '.') }}</td>
                                    </tr>
                                    <tr>
                                        <th scope="row">Sold</th>
                                        <td>
                                            @if (count($item->sold) < 1)
                                                0
                                            @elseif ($item->sold[0]->total_sold == 0)
                                                0
                                            @else
                                                {{ $item->sold[0]->total_sold }}
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <th scope="row">Created at</th>
                                        <td>{{ $item->created_at }}</td>
                                    </tr>
                                    <tr>
                                        <th scope="row">Update at</th>
                                        <td>{{ $item->updated_at }}</td>
                                    </tr>
                                </tbody>
                            </table>
                            <div class="float-end">
                                <a href="/admin/products/edit/{{ $item->id }}"><button type="button"
                                        class="btn btn-primary"><i class="bi bi-pen-fill"></i> Edit</button></a>
                            </div>
                        @endif
                    @endforeach
                </main>
            </div>
        </div>
    @endsection
