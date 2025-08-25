@extends('layouts.main')

@section('title')
    {{ __('messages.edit_driver') }}
@endsection

@section('content')
    <div class="row">
        <div class="col-lg-12 mb-4">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex justify-content-between align-items-center">
                    <h6 class="m-0 font-weight-bold text-primary">{{ __('messages.edit_driver') }}</h6>
                    <a href="{{ route('drivers.index') }}" class="btn btn-primary">{{ __('messages.back') }}</a>
                </div>
                <div class="card-body">
                    @include('layouts.errors')
                    <form action="{{ route('drivers.update', $driver->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <!-- Driver Data -->
                        <div class="row m-2">
                            <div class="form-group col-md-6">
                                <label for="name">{{ __('messages.name') }}</label>
                                <input type="text" class="form-control" name="name" id="name"
                                    placeholder="{{ __('messages.name') }}" value="{{ old('name', $driver->name) }}">
                            </div>
                            <div class="form-group col-md-6">
                                <label for="username">{{ __('messages.username') }}</label>
                                <input type="text" class="form-control" name="username" id="username"
                                    placeholder="{{ __('messages.username') }}"
                                    value="{{ old('username', $driver->username) }}">
                            </div>
                        </div>

                        <div class="row m-2">
                            <div class="form-group col-md-6">
                                <label for="nid">{{ __('messages.nid') }}</label>
                                <input type="number" class="form-control" name="nid" id="nid"
                                    placeholder="{{ __('messages.nid') }}" value="{{ old('nid', $driver->nid) }}">
                            </div>
                            <div class="form-group col-md-6">
                                <label for="status">{{ __('messages.user_status') }}</label>
                                <select name="status" id="status" class="form-control">
                                    <option value="1" {{ old('status', $driver->status) == 1 ? 'selected' : '' }}>
                                        {{ __('messages.active') }}
                                    </option>
                                    <option value="0" {{ old('status', $driver->status) == 0 ? 'selected' : '' }}>
                                        {{ __('messages.disabled') }}
                                    </option>
                                </select>
                            </div>
                        </div>
                        <div class="row m-2">
                            <div class="form-group col-md-6">
                                <label for="password">{{ __('messages.password') }}</label>
                                <input type="password" class="form-control" name="password" id="password"
                                    placeholder="{{ __('messages.password') }}">
                            </div>
                            <div class="form-group col-md-6">
                                <label for="password_confirmation">{{ __('messages.password_confirmation') }}</label>
                                <input type="password" class="form-control" name="password_confirmation"
                                    id="password_confirmation" placeholder="{{ __('messages.password_confirmation') }}">
                            </div>
                        </div>
                        <div class="row m-2">
                            <div class="form-group col-md-6">
                                <label for="image">{{ __('messages.driver_image') }}</label>
                                <input type="file" class="form-control" name="image" id="image">
                                <small class="form-text text-danger">{{ __('messages.image_help_text') }}</small>
                            </div>
                            <div class="form-group col-md-6">
                                <label for="image">{{ __('messages.mobile') }}</label>
                                <input type="text" class="form-control" name="mobile" id="mobile" value="{{$driver->mobile}}">
                            </div>
                            <div class="form-group col-md-6">
                                <label for="notes">{{ __('messages.notes') }}</label>
                                <textarea class="form-control" name="notes" id="notes" rows="3">{{ old('notes', $driver->notes) }}</textarea>
                            </div>
                        </div>

                        <hr>

                        <!-- Vehicle Data -->
                        <h5 class="mt-4">{{ __('messages.vehicle_details') }}</h5>
                        <div class="row m-2">
                            <div class="form-group col-md-6">
                                <label for="vehichle_color">{{ __('messages.vehicle_color') }}</label>
                                <input type="text" class="form-control" name="vehichle_color" id="vehichle_color"
                                    value="{{ old('vehichle_color', $driver->vehichle_color) }}">
                            </div>
                            <div class="form-group col-md-6">
                                <label for="vehichle_number">{{ __('messages.vehicle_number') }}</label>
                                <input type="text" class="form-control" name="vehichle_number" id="vehichle_number"
                                    value="{{ old('vehichle_number', $driver->vehichle_number) }}">
                            </div>
                        </div>

                        <div class="row m-2">
                            <div class="form-group col-md-6">
                                <label for="vehichle_brand">{{ __('messages.vehicle_brand') }}</label>
                                <input type="text" class="form-control" name="vehichle_brand" id="vehichle_brand"
                                    value="{{ old('vehichle_brand', $driver->vehichle_brand) }}">
                            </div>
                            <div class="form-group col-md-6">
                                <label for="vehichle_type">{{ __('messages.vehicle_type') }}</label>
                                <input type="text" class="form-control" name="vehichle_type" id="vehichle_type"
                                    value="{{ old('vehichle_type', $driver->vehichle_type) }}">
                            </div>
                        </div>

                        <div class="row m-2">
                            <div class="form-group col-md-6">
                                <label for="vehichle_model">{{ __('messages.vehicle_model') }}</label>
                                <input type="text" class="form-control" name="vehichle_model" id="vehichle_model"
                                    value="{{ old('vehichle_model', $driver->vehichle_model) }}">
                            </div>
                            <div class="form-group col-md-6">
                                <label for="vehichle_model_year">{{ __('messages.vehicle_model_year') }}</label>
                                <input type="number" class="form-control" name="vehichle_model_year"
                                    id="vehichle_model_year"
                                    value="{{ old('vehichle_model_year', $driver->vehichle_model_year) }}">
                            </div>
                        </div>

                        <div class="row m-2">
                            <div class="form-group col-md-6">
                                <label for="vehichle_license_ends_at">{{ __('messages.vehicle_license_ends_at') }}</label>
                                <input type="date" class="form-control" name="vehichle_license_ends_at"
                                    id="vehichle_license_ends_at"
                                    value="{{ old('vehichle_license_ends_at', $driver->vehichle_license_ends_at) }}">
                            </div>
                        </div>
                        <hr>

                        <!-- License Information -->
                        <h5 class="mt-4">{{ __('messages.license_details') }}</h5>
                        <div class="row m-2">
                            <div class="form-group col-md-6">
                                <label for="licence_name">{{ __('messages.licence_name') }}</label>
                                <input type="text" class="form-control" name="licence_name" id="licence_name"
                                    value="{{ old('licence_name', $driver->licence_name) }}">
                            </div>
                            <div class="form-group col-md-6">
                                <label for="licence_grade">{{ __('messages.licence_grade') }}</label>
                                <input type="text" class="form-control" name="licence_grade" id="licence_grade"
                                    value="{{ old('licence_grade', $driver->licence_grade) }}">
                            </div>
                        </div>

                        <div class="row m-2">
                            <div class="form-group col-md-6">
                                <label for="licence_issue_date">{{ __('messages.licence_issue_date') }}</label>
                                <input type="date" class="form-control" name="licence_issue_date"
                                    id="licence_issue_date"
                                    value="{{ old('licence_issue_date', $driver->licence_issue_date) }}">
                            </div>
                            <div class="form-group col-md-6">
                                <label for="licence_end_date">{{ __('messages.licence_end_date') }}</label>
                                <input type="date" class="form-control" name="licence_end_date" id="licence_end_date"
                                    value="{{ old('licence_end_date', $driver->licence_end_date) }}">
                            </div>
                        </div>
                        <hr>

                        <div class="row text-center">
                            <button type="submit" class="btn btn-primary m-auto">{{ __('messages.update') }}</button>
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

@section('navbar')
    @include('layouts.navbar')
@endsection
