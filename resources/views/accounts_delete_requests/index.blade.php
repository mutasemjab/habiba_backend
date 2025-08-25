@extends('layouts.main')
@section('navbar')
    @include('layouts.navbar')
@endsection
@section('sidebar')
    @include('layouts.sidebar')
@endsection
@section('title')
    {{ __('messages.accounts_delete_requests') }}
@endsection
@section('content')
    <div class="row">
        <div class="col-lg-12 mb-4">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex justify-content-between align-items-center">
                    <h6 class="m-0 font-weight-bold text-primary">{{ __('messages.accounts_delete_requests') }}</h6>
                </div>

                <div class="card-body">
                    <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>{{ __('messages.user_name') }}</th>
                                <th>{{ __('messages.mobile') }}</th>
                                <th>{{ __('messages.comment') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach (App\Models\AccountDeleteRequest::all() as $del_request)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $del_request->user_name }}</td>
                                    <td>{{ $del_request->mobile }}</td>
                                    <td>{{ $del_request->comment }}</td>
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
