@extends('layouts.main')

@section('title')
    {{ __('messages.add_coupon') }}
@endsection
@section('navbar')
    @include('layouts.navbar')
@endsection
@section('sidebar')
    @include('layouts.sidebar')
@endsection
@section('content')
    <div class="row">
        <div class="col-lg-12 mb-4">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex justify-content-between align-items-center">
                    <h6 class="m-0 font-weight-bold text-primary">{{ __('messages.add_coupon') }}</h6>
                    <a href="{{ route('coupons.index') }}" class="btn btn-primary">{{ __('messages.back') }}</a>
                </div>
                <div class="card-body">
                    @include('layouts.errors')
                    <form action="{{ route('coupons.store') }}" method="POST">
                        @csrf
                        <div class="form-group">
                            <label for="title">{{ __('messages.coupon_title') }}</label>
                            <input type="text" class="form-control" name="title" id="title"
                                aria-describedby="titleHelp" placeholder="{{ __('messages.coupon_title') }}">
                        </div>
                        <div class="form-group">
                            <label for="code">{{ __('messages.coupon_code') }}</label>
                            <input type="text" class="form-control" name="code" id="code"
                                aria-describedby="codeHelp" placeholder="{{ __('messages.coupon_code') }}">
                        </div>
                        <div class="form-group">
                            <label for="persentage">{{ __('messages.coupon_persentage') }}</label>
                            <input type="number" class="form-control" name="persentage" id="persentage"
                                aria-describedby="persentageHelp" placeholder="{{ __('messages.coupon_persentage') }}">
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="start_at">{{ __('messages.coupon_start_at') }}</label>
                                    <input type="date" class="form-control" name="start_at" id="start_at"
                                        aria-describedby="start_atHelp" placeholder="{{ __('messages.coupon_start_at') }}">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="end_at">{{ __('messages.coupon_end_at') }}</label>
                                    <input type="date" class="form-control" name="end_at" id="end_at"
                                        aria-describedby="end_atHelp" placeholder="{{ __('messages.coupon_end_at') }}">
                                </div>
                            </div>
                        </div>

                        <hr>
                        <div class="row text-center">
                            <button type="submit" class="btn btn-primary m-auto">{{ __('messages.submit') }}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
