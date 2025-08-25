@extends('layouts.main')

@section('title')
    {{ __('messages.edit_coupon') }}
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
                    <h6 class="m-0 font-weight-bold text-primary">{{ __('messages.edit_coupon') }}</h6>
                    <a href="{{ route('coupons.index') }}" class="btn btn-primary">{{ __('messages.back') }}</a>
                </div>
                <div class="card-body">
                    @include('layouts.errors')
                    <form action="{{ route('coupons.update', $coupon->id) }}" method="POST">
                        @csrf
                        <div class="form-group">
                            <label for="title">{{ __('messages.title') }}</label>
                            <input type="text" class="form-control" name="title" id="title"
                                aria-describedby="titleHelp" placeholder="{{ __('messages.coupon_title') }}"
                                value="{{ $coupon->title }}">
                        </div>
                        <div class="form-group">
                            <label for="code">{{ __('messages.code') }}</label>
                            <input type="text" class="form-control" name="code" id="code"
                                aria-describedby="codeHelp" placeholder="{{ __('messages.coupon_code') }}"
                                value="{{ $coupon->code }}">
                        </div>
                        <div class="form-group">
                            <label for="persentage">{{ __('messages.persentage') }}</label>
                            <input type="number" class="form-control" name="persentage" id="persentage"
                                aria-describedby="persentageHelp" placeholder="{{ __('messages.persentage') }}"
                                value="{{ $coupon->persentage }}">
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="start_at">{{ __('messages.start_at') }}</label>
                                    <input type="date" class="form-control" name="start_at" id="start_at"
                                        aria-describedby="start_atHelp" placeholder="{{ __('messages.start_at') }}"
                                        value="{{ $coupon->start_at }}">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="end_at">{{ __('messages.end_at') }}</label>
                                    <input type="date" class="form-control" name="end_at" id="end_at"
                                        aria-describedby="end_atHelp" placeholder="{{ __('messages.end_at') }}"
                                        value="{{ $coupon->end_at }}">
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
