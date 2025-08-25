@extends('layouts.main')
@section('navbar')
    @include('layouts.navbar')
@endsection
@section('sidebar')
    @include('layouts.sidebar')
@endsection
@section('title')
    {{ __('messages.coupons_index') }}
@endsection
@section('content')
    <!-- Page Heading -->
    <div class="row">
        <div class="col-lg-12 mb-4">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex justify-content-between align-items-center">
                    <h6 class="m-0 font-weight-bold text-primary">{{ __('messages.coupons_index') }}</h6>
                    <a href="{{ route('coupons.create') }}" class="btn btn-primary">{{ __('messages.add_coupon') }}</a>
                </div>

                <div class="card-body">
                    <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>{{ __('messages.coupon_title') }}</th>
                                <th>{{ __('messages.coupon_code') }}</th>
                                <th>{{ __('messages.coupon_persentage') }}</th>
                                <th>{{ __('messages.coupon_expire_at') }}</th>
                                <th>{{ __('messages.actions') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($coupons as $coupon)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $coupon->title }}</td>
                                    <td>{{ $coupon->code }}</td>
                                    <td>{{ $coupon->persentage }} %</td>
                                    <td>{{ $coupon->end_at }}</td>
                                    <td>
                                        <a href="{{ route('coupons.edit', $coupon->id) }}"
                                            class="btn btn-primary btn-sm">{{ __('messages.edit') }}</a>
                                        <form action="{{ route('coupons.destroy', $coupon->id) }}" method="POST"
                                            class="d-inline">
                                            @csrf
                                            <button type="submit" class="btn btn-danger btn-sm"
                                                onclick="return confirm('Are you sure?')">{{ __('messages.delete') }}</button>
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
