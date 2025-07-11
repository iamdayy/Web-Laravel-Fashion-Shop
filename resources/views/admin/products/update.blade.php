@extends('admin.layout')
@section('content_admin')

    <body>
        <div class="mb-5 container-fluid">
            <div class="row">
                @include('admin.components.sidebar')
                <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
                    <div
                        class="flex-wrap pt-3 pb-2 mb-3 d-flex justify-content-between flex-md-nowrap align-items-center border-bottom">
                    </div>
                    <form action="/admin/products/update" method="post" enctype="multipart/form-data" onsubmit="createAlert()">
                        {{ csrf_field() }}
                        <input type="hidden" name="id" value="{{ $item->id }}">
                        <div class="mb-3 text-center">
                            <img src="{{ $item->photo }}" width="250" alt="">
                        </div>
                        <div class="mb-3">
                            <label for="newPhoto" class="form-label">Product Photo</label>
                            <input type="file" class="form-control" id="newPhoto" value="{{ $item->photo }}"
                                name="newPhoto" placeholder="Product Photo">
                        </div>
                        <div class="mb-3">
                            <input type="hidden" class="form-control" id="photo" value="{{ $item->photo }}"
                                name="photo" placeholder="Product photo">
                        </div>
                        <div class="mb-3">
                            <label for="name" class="form-label">Product Name</label>
                            <input type="text" class="form-control" id="name" value="{{ $item->name }}"
                                name="name" placeholder="Product name">
                        </div>
                        <div class="mb-3">
                            <label for="description" class="form-label">Description</label>
                            <textarea class="form-control" id="description" name="description" placeholder="Description" rows="3">{{ $item->description }}</textarea>
                        </div>
                        <div class="mb-3">
                            <label for="stock" class="form-label">Stock</label>
                            <input type="number" class="form-control" id="stock" value="{{ $item->stock }}"
                                name="stock" placeholder="Stock">
                        </div>
                        <div class="mb-3">
                            <label for="price" class="form-label">Price</label>
                            <input type="number" class="form-control" id="price" name="price"
                                value="{{ $item->price }}" placeholder="Price">
                        </div>
                        <div class="mb-3">
                            <label for="category" class="form-label">Category</label>
                            <select id="category" class="mb-5 form-select" name="category"
                                aria-label="Default select example">
                                <option value="{{ $item->category }}" selected>{{ Str::ucfirst($item->category) }}</option>
                                <option value="women">Women</option>
                                <option value="men">Men</option>
                                <option value="children">Children</option>
                                <option value="accessories">Accessories</option>
                                <option value="shoes">Shoes</option>
                                <option value="other">Other</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <input class="form-control" id="sold" type="hidden" value="{{ $item->created_at }}"
                                placeholder="-" aria-label="Disabled input sold" disabled>
                        </div>
                        <button type="submit" class="btn btn-success float-end">Submit</button>
                    </form>
                </main>
            </div>
        </div>
    @endsection
