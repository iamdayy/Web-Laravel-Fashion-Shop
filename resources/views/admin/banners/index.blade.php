@extends('admin.layout')
@section('content_admin')
    <div class="container-fluid">
        <div class="row">
            @include('admin.components.sidebar')

            <main class="my-5 col-md-9 ms-sm-auto col-lg-10 px-md-4">
                @include('admin.banners.table_banners')
            </main>
        </div>
    </div>
@endsection
