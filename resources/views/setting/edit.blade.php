@extends('layouts.main')

@section('title')
    {{ __('messages.edit_setting') }}
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
                    <h6 class="m-0 font-weight-bold text-primary">{{ __('Edit setting') }}</h6>
                    <a href="{{ route('settings.index') }}" class="btn btn-primary">{{ __('Back') }}</a>
                </div>
                <div class="card-body">
                    @include('layouts.errors')

                    <!-- Form to update setting -->
                    <form action="{{ route('settings.update', $setting->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <!-- setting Name Input -->
                        <div class="form-group">
                            <label for="setting_name">{{ __('messages.min_order') }}</label>
                            <input type="text" class="form-control" name="min_order" id="setting_name"
                                aria-describedby="setting_nameHelp" value="{{ $setting->min_order }}"
                                placeholder=">{{ __('messages.min_order') }}">
                        </div>
                       
                        <hr>
                        <!-- Submit Button -->
                        <div class="row text-center">
                            <button type="submit" class="btn btn-primary m-auto">{{ __('messages.submit') }}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
