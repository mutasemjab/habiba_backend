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
                    @if ($siteGenerals)
                        <a href="{{ route('site_generals.edit', $siteGenerals->id) }}" class="btn btn-primary">
                            {{ __('messages.edit') }}
                        </a>
                    @else
                        <a href="{{ route('site_generals.create') }}" class="btn btn-primary">
                            {{ __('messages.edit') }}
                        </a>
                    @endif
                </div>

                <div class="card-body">
                    @include('layouts.errors')

                    <div class="form-group">
                        <label for="status">فتح / اغلاق المتجر</label>
                        <input type="text" class="form-control" id="status"
                            value="{{ $siteGenerals->status == 1 ? "ON" : "OFF" }}" readonly>
                    </div>
                    <div class="form-group">
                        <label for="min_order">{{ __('messages.min_order') }}</label>
                        <input type="text" class="form-control" id="min_order"
                            value="{{ $siteGenerals->min_order ?? 'N/A' }}" readonly>
                    </div>
                  
                    <div class="form-group">
                        <label for="whatsapp_link">{{ __('messages.whatsapp_link') }}</label>
                        <input type="text" class="form-control" id="whatsapp_link"
                            value="{{ $siteGenerals->whatsapp_link ?? 'N/A' }}" readonly>
                    </div>

                    <div class="form-group">
                        <label for="facebook_link">{{ __('messages.facebook_link') }}</label>
                        <input type="text" class="form-control" id="facebook_link"
                            value="{{ $siteGenerals->facebook_link ?? 'N/A' }}" readonly>
                    </div>

                    <div class="form-group">
                        <label for="instagram_link">{{ __('messages.instagram_link') }}</label>
                        <input type="text" class="form-control" id="instagram_link"
                            value="{{ $siteGenerals->instagram_link ?? 'N/A' }}" readonly>
                    </div>

                    <div class="form-group">
                        <label for="delivery_price">{{ __('messages.delivery_price') }}</label>
                        <input type="number" class="form-control" id="delivery_price"
                            value="{{ $siteGenerals->delivery_price ?? 0 }}" readonly>
                    </div>

                    <hr>

                    <div class="form-group">
                        <label for="terms">{{ __('messages.terms') }}</label>
                        <textarea id="terms" cols="30" rows="4" class="form-control" readonly>{{ $siteGenerals->terms ?? 'N/A' }}</textarea>
                    </div>

                    <hr>

                    <div class="form-group">
                        <label for="about_us">{{ __('messages.about_us') }}</label>
                        <textarea id="about_us" cols="30" rows="4" class="form-control" readonly>{{ $siteGenerals->about_us ?? 'N/A' }}</textarea>
                    </div>

                    <hr>

                    <div class="form-group">
                        <label for="return_policy">{{ __('messages.return_policy') }}</label>
                        <textarea id="return_policy" cols="30" rows="4" class="form-control" readonly>{{ $siteGenerals->return_policy ?? 'N/A' }}</textarea>
                    </div>
                    <hr>
                    <div class="form-group">
                        <label for="onboarding_1">{{ __('messages.onboarding_1') }}</label>
                        <input class="form-control" name='onboarding_1' type='text' id='onboarding_1'
                            value="{{ $siteGenerals->onboarding_1 ?? 'N/A' }}" readonly>
                    </div>
                    <div class="form-group">
                        <label for="onboarding_2">{{ __('messages.onboarding_2') }}</label>
                        <input class="form-control" name='onboarding_2' type='text' id='onboarding_2'
                            value="{{ $siteGenerals->onboarding_2 ?? 'N/A' }}" readonly>
                    </div>
                    <div class="form-group">
                        <label for="onboarding_3">{{ __('messages.onboarding_3') }}</label>
                        <input class="form-control" name='onboarding_3' type='text' id='onboarding_3'
                            value="{{ $siteGenerals->onboarding_3 ?? 'N/A' }}" readonly>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
