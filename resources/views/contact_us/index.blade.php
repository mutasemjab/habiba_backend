@extends('layouts.main')
@section('navbar')
    @include('layouts.navbar')
@endsection
@section('sidebar')
    @include('layouts.sidebar')
@endsection
@section('title')
    {{ __('messages.contact_us_requests') }}
@endsection
@section('content')
    <div class="row">
        <div class="col-lg-12 mb-4">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex justify-content-between align-items-center">
                    <h6 class="m-0 font-weight-bold text-primary">{{ __('messages.contact_us_requests') }}</h6>
                </div>

                <div class="card-body">
                    <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>{{ __('messages.user_name') }}</th>
                                <th>{{ __('messages.mobile') }}</th>
                                <th>{{ __('messages.subject') }}</th>
                                <th>{{ __('messages.message') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach (App\Models\ContactUs::all() as $contact_us)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $contact_us->user_name }}</td>
                                    <td>{{ $contact_us->mobile }}</td>
                                    <td>{{ $contact_us->subject }}</td>
                                    <td>{{ $contact_us->message }}</td>
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
