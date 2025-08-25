@extends('layouts.main')

@section('title')
    {{ __('messages.new_role') }}
@endsection
@section('navbar')
    @include('layouts.navbar')
@endsection
@section('content')
    <div class="row">
        <div class="col-lg-12 mb-4">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex justify-content-between align-items-center">
                    <h6 class="m-0 font-weight-bold text-primary">{{ __('messages.edit_role') }}</h6>
                    <a href="{{ route('roles.index') }}" class="btn btn-primary">{{ __('messages.back') }}</a>
                </div>
                <div class="card-body">
                    @include('layouts.errors')
                    <form action="{{ route('roles.update', $role->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="form-group">
                            <label for="name">{{ __('messages.role_name') }}</label>
                            <input type="text" class="form-control" name="name" id="name"
                                aria-describedby="nameHelp" placeholder="{{ __('messages.role_name') }}"
                                value="{{ $role->name }}">
                        </div>
                        <hr>
                        <h3 class="text-center">{{ __('messages.permissions') }}</h3>
                        <div class="row p-2">
                            @foreach ($permissions as $p)
                                <div class="form-check form-switch col-md-3">
                                    <input class="form-check-input" name="permission[]" type="checkbox" role="switch"
                                        id="{{ $p->name }}" @if (in_array($p->id, $rolePermissions)) checked @endif
                                        value="{{ $p->name }}">
                                    <label class="form-check-label"
                                        for="{{ $p->name }}">{{ __('messages.' . $p->name) }}</label>
                                </div>
                            @endforeach
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
