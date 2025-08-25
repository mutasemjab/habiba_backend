@extends('layouts.main')
@section('title')
    {{ __('messages.edit_user') }}
@endsection
@section('content')
    <div class="row">
        <div class="col-lg-12 mb-4">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex justify-content-between align-items-center">
                    <h6 class="m-0 font-weight-bold text-primary">{{ __('messages.edit_user') }}</h6>
                    <a href="{{ route('users.index') }}" class="btn btn-primary">{{ __('messages.back') }}</a>
                </div>
                <div class="card-body">
                    @include('layouts.errors')
                    <form action="{{ route('users.update', $user->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="form-group col-md-4 col-lg-4 col-sm-12">
                                <label for="name">{{ __('User Name') }}</label>
                                <input type="text" class="form-control" name="name" id="name"
                                    aria-describedby="nameHelp" placeholder="Name" value="{{ $user->name }}">
                            </div>
                            <div class="form-group col-md-4 col-lg-4 col-sm-12">
                                <label for="email">{{ __('User Email') }}</label>
                                <input type="email" class="form-control" name="email" id="email"
                                    aria-describedby="emailHelp" placeholder="Email" value="{{ $user->email }}">
                            </div>
                            <div class="form-group col-md-4 col-lg-4 col-sm-12">
                                <label for="mobile">{{ __('User Mobile') }}</label>
                                <input type="text" class="form-control" name="mobile" id="mobile"
                                    aria-describedby="mobileHelp" placeholder="Mobile" value="{{ $user->mobile }}">
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-md-4 col-lg-4 col-sm-12">
                                <label for="password">{{ __('Password') }}</label>
                                <input type="password" class="form-control" name="password" id="password"
                                    aria-describedby="passwordHelp" placeholder="Password">
                            </div>
                            <div class="form-group col-md-4 col-lg-4 col-sm-12">
                                <label for="password">{{ __('Password Confirmation') }}</label>
                                <input type="password" class="form-control" name="password_confirmation" id="password"
                                    aria-describedby="passwordHelp" placeholder="Password">
                            </div>
                            {{-- <div class="col-xs-12 col-sm-12 col-md-12">
                                <div class="form-group">
                                    <strong>Role:</strong>
                                    {!! Form::select('roles[]', $roles, $userRole, ['class' => 'form-control', 'multiple']) !!}
                                </div>
                            </div>
                            {{-- <div class="col-xs-12 col-sm-12 col-md-12">
                                <div class="form-group">
                                    <strong>Role:</strong>
                                    <select name="roles[]" class="form-control">
                                        @foreach ($roles as $role)
                                        <option value="{{ $role }}"
                                        @if (in_array($role, $userRole)) selected @endif>
                                        {{ $role }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                        </div> --}}
                            <div class="form-group col-md-4 col-lg-4 col-sm-12">
                                <label for="role_id">{{ __('User Role') }}</label>
                                <select name="roles[]" class="form-control">
                                    @foreach ($roles as $role)
                                        <option value="{{ $role }}"
                                            @if (in_array($role, $userRole)) selected @endif>
                                            {{ $role }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-md-4 col-lg-4 col-sm-12">
                                <label for="status">{{ __('messages.user_status') }}</label>
                                <select name="status" id="status" class="form-control">
                                    <option value="{{ $user->status }}" selected hidden>
                                        @if ($user->status == 1)
                                            {{ __('messages.active') }}
                                        @else
                                            {{ __('messages.disabled') }}
                                        @endif
                                    </option>
                                    <option value="1">{{ __('messages.active') }}</option>
                                    <option value="0">{{ __('messages.disabled') }}</option>
                                </select>
                            </div>
                        </div>
                        <hr>
                        <div class="row text-center">
                            <button type="submit" class="btn btn-primary m-auto">{{ __('Submit') }}</button>
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
