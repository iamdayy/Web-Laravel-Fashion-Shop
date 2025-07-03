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
                    <form action="/admin/offers/update/{{ $item->id }}" method="post" enctype="multipart/form-data"
                        onsubmit="createAlert()">
                        {{ csrf_field() }}
                        <input type="hidden" name="id" value="{{ $item->id }}">
                        <div class="mb-3">
                            <label for="title" class="form-label">Offer Title</label>
                            <input type="text" class="form-control" id="title" name="title"
                                placeholder="Offer Title" value="{{ $item->title }}" required aria-required="true">
                        </div>
                        <div class="mb-3">
                            <label for="description" class="form-label">Description</label>
                            <textarea class="form-control" id="description" name="description" rows="3" placeholder="Offer Description"
                                required aria-required="true">{{ $item->description }}</textarea>
                        </div>
                        <div class="mb-3">
                            <label for="discount_percentage" class="form-label">Discount Percentage</label>
                            <input type="number" class="form-control" id="discount_percentage" name="discount_percentage"
                                placeholder="Discount Percentage" step="0.01" min="0" max="100"
                                value="{{ $item->discount_percentage }}" required aria-required="true">
                        </div>
                        <div class="mb-3">
                            <label for="start_date" class="form-label">Start Date</label>
                            <input type="date" class="form-control" id="start_date" name="start_date"
                                placeholder="Start Date" value="{{ $item->start_date }}" required aria-required="true">
                        </div>
                        <div class="mb-3">
                            <label for="end_date" class="form-label">End Date</label>
                            <input type="date" class="form-control" id="end_date" name="end_date" placeholder="End Date"
                                value="{{ $item->end_date }}" required aria-required="true">
                        </div>
                        <div class="mb-3">
                            <label for="image" class="form-label">Offer Image</label>
                            <input type="file" class="form-control" id="image" name="image"
                                placeholder="Offer Image" required aria-required="true">
                        </div>
                        <div class="mb-3">
                            <label for="link" class="form-label">Link</label>
                            <input type="url" class="form-control" id="link" name="link"
                                placeholder="Link to Offer Details or Redemption" value="{{ $item->link }}"
                                aria-required="true">
                        </div>
                        <hr class="mt-4">
                        <button type="submit" class="btn btn-success float-end">Submit</button>
                    </form>
                </main>
            </div>
        </div>
    @endsection
