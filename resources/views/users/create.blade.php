@extends('layouts.main')

@section('title')
    {{ __('messages.create_new_user') }}
@endsection
@section('navbar')
    @include('layouts.navbar')
@endsection
@section('content')
    <div class="row">
        <div class="col-lg-12 mb-4">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex justify-content-between align-items-center">
                    <h6 class="m-0 font-weight-bold text-primary">{{ __('messages.create_new_user') }}</h6>
                    <a href="{{ route('users.index') }}" class="btn btn-primary">{{ __('messages.back') }}</a>
                </div>
                <div class="card-body">
                    @include('layouts.errors')
                    <form action="{{ route('users.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="form-group col-md-4 col-lg-4 col-sm-12">
                                <label for="name">{{ __('messages.user_name') }}</label>
                                <input type="text" class="form-control" name="name" id="name"
                                    aria-describedby="nameHelp" placeholder="{{ __('user_name') }}"
                                    value="{{ old('name') }}">
                            </div>
                            <div class="form-group col-md-4 col-lg-4 col-sm-12">
                                <label for="email">{{ __('messages.email') }}</label>
                                <input type="email" class="form-control" name="email" id="email"
                                    aria-describedby="emailHelp" placeholder="{{ __('messages.email') }}"
                                    value="{{ old('email') }}">
                            </div>
                            <div class="form-group col-md-4 col-lg-4 col-sm-12">
                                <label for="mobile">{{ __('messages.mobile') }}</label>
                                <input type="text" class="form-control" name="mobile" id="mobile"
                                    aria-describedby="mobileHelp" placeholder="{{ __('messages.mobile') }}"
                                    value="{{ old('mobile') }}">
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-md-4 col-lg-4 col-sm-12">
                                <label for="password">{{ __('messages.password') }}</label>
                                <input type="password" class="form-control" name="password" id="password"
                                    aria-describedby="passwordHelp" placeholder="{{ __('messages.password') }}">
                            </div>
                            <div class="form-group col-md-4 col-lg-4 col-sm-12">
                                <label for="password">{{ __('messages.password_confirmation') }}</label>
                                <input type="password" class="form-control" name="password_confirmation" id="password"
                                    aria-describedby="passwordHelp"
                                    placeholder="{{ __('messages.password_confirmation') }}">
                            </div>
                            <div class="form-group col-md-4 col-lg-4 col-sm-12">
                                <label for="role_id">{{ __('messages.user_role') }}</label>
                                <select name="roles[]" id="roles[]" class="form-control">
                                    <option value="" disabled selected hidden>{{ __('messages.user_role') }}</option>
                                    @foreach ($roles as $role)
                                        <option value="{{ $role->name }}">{{ $role->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-md-4 col-lg-4 col-sm-12">
                                <label for="status">{{ __('messages.user_status') }}</label>
                                <select name="status" id="status" class="form-control">
                                    <option value="" disabled selected hidden>{{ __('messages.user_status') }}
                                    </option>
                                    <option value="1">{{ __('messages.active') }}</option>
                                    <option value="0">{{ __('messages.disabled') }}</option>
                                </select>
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
@section('sidebar')
    @include('layouts.sidebar')
@endsection
