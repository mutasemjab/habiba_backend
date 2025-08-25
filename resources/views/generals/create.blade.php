@extends('layouts.main')

@section('title')
    {{ __('messages.site_generals') }}
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
                    <h6 class="m-0 font-weight-bold text-primary">{{ __('messages.site_generals') }}</h6>
                    <a href="{{ route('site_generals.index') }}" class="btn btn-primary">{{ __('messages.back') }}</a>
                </div>
                <div class="card-body">
                    @include('layouts.errors')
                    <form action="{{ route('site_generals.store') }}" method="POST">
                        @csrf
                        <div class="form-group">
                            <label for="title">{{ __('messages.min_order') }}</label>
                            <input type="text" class="form-control" name="min_order" id="title"
                                aria-describedby="titleHelp" placeholder="{{ __('messages.min_order') }}">
                        </div>
                        <div class="form-group">
                            <label for="title">{{ __('messages.whatsapp_link') }}</label>
                            <input type="text" class="form-control" name="whatsapp_link" id="title"
                                aria-describedby="titleHelp" placeholder="{{ __('messages.whatsapp_link') }}">
                        </div>
                        <div class="form-group">
                            <label for="title">{{ __('messages.facebook_link') }}</label>
                            <input type="text" class="form-control" name="facebook_link" id="title"
                                aria-describedby="titleHelp" placeholder="{{ __('messages.facebook_link') }}">
                        </div>
                        <div class="form-group">
                            <label for="title">{{ __('messages.instagram_link') }}</label>
                            <input type="text" class="form-control" name="instagram_link" id="title"
                                aria-describedby="titleHelp" placeholder="{{ __('messages.instagram_link') }}">
                        </div>
                        <div class="form-group">
                            <label for="title">{{ __('messages.delivery_price') }}</label>
                            <input type="number" class="form-control" name="delivery_price" id="title"
                                aria-describedby="titleHelp" placeholder="{{ __('messages.delivery_price') }}">
                        </div>
                        <hr>
                        <div class="form-group">
                            <label for="terms">{{ __('messages.terms') }}</label>
                            <textarea name="terms" id="terms" cols="30" rows="10" class="form-control"></textarea>
                        </div>
                        <hr>
                        <div class="form-group">
                            <label for="about_us">{{ __('messages.about_us') }}</label>
                            <textarea name="about_us" id="about_us" cols="30" rows="10" class="form-control"></textarea>
                        </div>
                        <hr>
                        <div class="form-group">
                            <label for="return_policy">{{ __('messages.return_policy') }}</label>
                            <textarea name="return_policy" id="return_policy" cols="30" rows="10" class="form-control"></textarea>
                        </div>
                        <div class="form-group">
                            <label for="onboarding_1">{{ __('messages.onboarding_1') }}</label>
                            <input class="form-control" name='onboarding_1' type='text' id='onboarding_1' {{ old('onboarding_1') }}>
                        </div>
                        <div class="form-group">
                            <label for="onboarding_2">{{ __('messages.onboarding_2') }}</label>
                            <input class="form-control" name='onboarding_2' type='text' id='onboarding_2' {{ old('onboarding_2') }}>
                        </div>
                        <div class="form-group">
                            <label for="onboarding_3">{{ __('messages.onboarding_3') }}</label>
                            <input class="form-control" name='onboarding_3' type='text' id='onboarding_3' {{ old('onboarding_3') }}>
                        </div>
                        <div class="row text-center">
                            <button type="submit" class="btn btn-primary m-auto">{{ __('messages.submit') }}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
