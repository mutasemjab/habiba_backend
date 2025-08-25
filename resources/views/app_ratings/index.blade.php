@extends('layouts.main')
@section('navbar')
    @include('layouts.navbar')
@endsection
@section('sidebar')
    @include('layouts.sidebar')
@endsection
@section('title')
    {{ __('messages.app_ratings') }}
@endsection
@section('content')
    <div class="row">
        <div class="col-lg-12 mb-4">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex justify-content-between align-items-center">
                    <h6 class="m-0 font-weight-bold text-primary">{{ __('messages.app_ratings') }}</h6>
                </div>

                <div class="card-body">
                    <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>{{ __('messages.user_name') }}</th>
                                <th>{{ __('messages.app_rate') }}</th>
                                <th>{{ __('messages.app_usage_rate') }}</th>
                                <th>{{ __('messages.delivery_rate') }}</th>
                                <th>{{ __('messages.quality_rate') }}</th>
                                <th>{{ __('messages.comment') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach (App\Models\AppRate::all() as $app_rate)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $app_rate->client->name }}</td>
                                    <td>{{ $app_rate->app_rate }}</td>
                                    <td>{{ $app_rate->app_usage_rate }}</td>
                                    <td>{{ $app_rate->delivery_rate }}</td>
                                    <td>{{ $app_rate->quality_rate }}</td>
                                    <td>{{ $app_rate->comment }}</td>
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
