@extends('layouts.main')
@section('navbar')
    @include('layouts.navbar')
@endsection
@section('sidebar')
    @include('layouts.sidebar')
@endsection
@section('title')
    {{ __('messages.categories') }}
@endsection
@section('content')
    @include('layouts.errors')
    @include('layouts.sessions')
    <!-- Page Heading -->
    <div class="row">
        <div class="col-lg-12 mb-4">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex justify-content-between align-items-center">
                    <h6 class="m-0 font-weight-bold text-primary">{{ __('messages.categories') }}</h6>
                    <a href="{{ route('categories.create') }}" class="btn btn-primary">{{ __('messages.new_category') }}</a>
                </div>

                <div class="card-body">
                    <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>{{ __('messages.image') }}</th>
                                <th>{{ __('messages.category_name') }}</th>
                                <th>{{ __('messages.ar_category_name') }}</th>
                                <th>{{ __('messages.order') }}</th>
                                <th>{{ __('messages.actions') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($categories as $cat)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td class="text-center"><img src="{{ asset("categories/images/$cat->image") }}"
                                            width="50vh" class='m-auto'></td>
                                    <td>{{ $cat->getEnName() }}</td>
                                    <td>{{ $cat->ar_category_name }}</td>
                                    <td>{{ $cat->order }}</td>
                                    <td>
                                        <a href="{{ route('categories.edit', $cat->id) }}"
                                            class="btn btn-primary btn-sm"><i class="fa-solid fa-pen-to-square"></i></a>
                                        <form action="{{ route('categories.destroy', $cat->id) }}" method="POST"
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
