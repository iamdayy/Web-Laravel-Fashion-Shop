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
                    <form action="/admin/banners/update/{{ $item->id }}" method="post" enctype="multipart/form-data"
                        onsubmit="createAlert()">
                        {{ csrf_field() }}
                        <input type="hidden" name="id" value="{{ $item->id }}">
                        <div class="mb-3">
                            <label for="image" class="form-label">Banner Image</label>
                            <input type="file" class="form-control" id="image" name="image"
                                placeholder="Banner Image" required aria-required="true">
                        </div>
                        <div class="mb-3">
                            <label for="redirectUrl" class="form-label">Redirect Url</label>
                            <input type="text" class="form-control" id="redirectUrl" name="redirectUrl"
                                placeholder="Redirect URL" value="{{ $item->redirectUrl }}" required aria-required="true">
                        </div>
                        <hr class="mt-4">
                        <button type="submit" class="btn btn-success float-end">Submit</button>
                    </form>
                </main>
            </div>
        </div>
    @endsection
