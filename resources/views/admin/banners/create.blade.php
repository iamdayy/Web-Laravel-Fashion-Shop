@extends('admin.layout')
@section('content_admin')
    <div class="mb-5 container-fluid">
        <div class="row">
            @include('admin.components.sidebar')

            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
                <div
                    class="flex-wrap pt-3 pb-2 mb-3 d-flex justify-content-between flex-md-nowrap align-items-center border-bottom">
                </div>
                <form action="/admin/banners/store" method="post" enctype="multipart/form-data" onsubmit="createAlert()">
                    {{ csrf_field() }}
                    <div class="mb-3">
                        <label for="image" class="form-label">Banner Image</label>
                        <input type="file" class="form-control" id="image" name="image" placeholder="Banner Image"
                            required aria-required="true">
                    </div>
                    <div class="mb-3">
                        <label for="redirectUrl" class="form-label">Redirect Url</label>
                        <input type="text" class="form-control" id="redirectUrl" name="redirectUrl"
                            placeholder="Redirect URL" required aria-required="true">
                    </div>
                    <hr class="mt-4">

                    <div id="container" class="mt-4 mb-3"> </div>

                    <button id='btn-submit' type="submit" class="btn btn-success float-end">Submit</button>
                </form>
            </main>
        </div>
    </div>
@endsection
