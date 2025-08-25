@extends('layouts.main')
@section('navbar')
    @include('layouts.navbar')
@endsection
@section('sidebar')
    @include('layouts.sidebar')
@endsection
@section('title')
    {{ __('messages.branches') }}
@endsection
@section('content')
    <!-- Page Heading -->
    <div class="row">
        <div class="col-lg-12 mb-4">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex justify-content-between align-items-center">
                    <h6 class="m-0 font-weight-bold text-primary">{{ __('Branches List') }}</h6>
                    <a href="{{ route('branches.create') }}" class="btn btn-primary">{{ __('messages.add_branch') }}</a>
                </div>

                <div class="card-body">
                    <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>{{ __('Branch Title') }}</th>
                                <th>{{ __('Longitude') }}</th>
                                <th>{{ __('Latitude') }}</th>
                                <th>{{ __('Actions') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($branches as $branch)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $branch->branch_title }}</td>
                                    <td>{{ $branch->branch_long }}</td>
                                    <td>{{ $branch->branch_lat }}</td>
                                    <td>
                                        <a href="{{ route('branches.edit', $branch->id) }}"
                                            class="btn btn-primary btn-sm">{{ __('Edit') }}</a>
                                        <form action="{{ route('branches.destroy', $branch->id) }}" method="POST"
                                            class="d-inline">
                                            @csrf
                                            <button type="submit" class="btn btn-danger btn-sm"
                                                onclick="return confirm('{{ __('Are you sure?') }}')">{{ __('Delete') }}</button>
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
