@extends('layouts.main')
@section('navbar')
    @include('layouts.navbar')
@endsection
@section('sidebar')
    @include('layouts.sidebar')
@endsection
@section('title')
    {{ __('messages.brands') }}
@endsection
@section('content')
    @include('layouts.errors')
    @include('layouts.sessions')
    <!-- Page Heading -->
    <div class="row">
        <div class="col-lg-12 mb-4">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex justify-content-between align-items-center">
                    <h6 class="m-0 font-weight-bold text-primary">{{ __('messages.brands_list') }}</h6>
                    <a href="{{ route('brands.create') }}" class="btn btn-primary">{{ __('messages.new_brand') }}</a>
                </div>

                <div class="card-body">
                    <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>{{ __('messages.image') }}</th>
                                <th>{{ __('messages.ar_brand_name') }}</th>
                                <th>{{ __('messages.brand_name') }}</th>
                                <th>{{ __('messages.actions') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($brands as $brand)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td><img src="{{ asset("brands/images/$brand->image") }}" width="100"></td>
                                    <td>{{ $brand->ar_brand_name }}</td>
                                    <td>{{ $brand->getEnName() }}</td>
                                    <td>
                                        <a href="{{ route('brands.edit', $brand->id) }}" class="btn btn-primary btn-sm"><i
                                                class="fa-solid fa-pen-to-square"></i></a>
                                        <form action="{{ route('brands.destroy', $brand->id) }}" method="POST"
                                            class="d-inline">
                                            @csrf
                                            <button type="submit" class="btn btn-danger btn-sm"
                                                onclick="return confirm('Are you sure?')"><i
                                                    class="fa-solid fa-trash"></i></button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
    <script>
        $(document).ready(function() {
            $('#dataTable').DataTable();
        });
    </script>
@endsection
