@extends('layouts.main')

@section('title')
    {{ __('messages.send_global_notification') }}
@endsection
@section('navbar')
    @include('layouts.navbar')
@endsection
@section('sidebar')
    @include('layouts.sidebar')
@endsection
@section('content')
    @include('layouts.sessions')
    <div class="row">
        <div class="col-lg-12 mb-4">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex justify-content-between align-items-center">
                    <h6 class="m-0 font-weight-bold text-primary">{{ __('messages.send_global_notification') }}</h6>
                </div>
                <div class="card-body">
                    @include('layouts.errors')
                    <form action="{{ route('send_global_notification') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="form-group">
                            <label for="sub_categories">{{ __('messages.notification_icon') }}</label>
                            <select name="icon" id="" class="form-control">
                                <option value="" disabled hidden selected>{{ __('messages.select_icon') }}</option>
                                <option value="offers">{{ __('messages.offers') }}</option>
                                <option value="rate">{{ __('messages.rate') }}</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="title">{{ __('messages.title') }}</label>
                            <input type="text" class="form-control" name="title" id="title"
                                aria-describedby="titleHelp" placeholder="{{ __('messages.title') }}">
                        </div>
                        <hr>
                        <div class="form-group">
                            <label for="message">{{ __('messages.message') }}</label>
                            <input type="text" class="form-control" name="message" id="message"
                                aria-describedby="messageHelp" placeholder="{{ __('messages.message') }}">
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
