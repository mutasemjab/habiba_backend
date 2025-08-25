@extends('layouts.main')

@section('title')
    {{ __('New Branch') }}
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
                    <h6 class="m-0 font-weight-bold text-primary">{{ __('New Branch') }}</h6>
                    <a href="{{ route('branches.index') }}" class="btn btn-primary">{{ __('Back') }}</a>
                </div>
                <div class="card-body">
                    @include('layouts.errors')
                    <form action="{{ route('branches.store') }}" method="POST">
                        @csrf
                        <div class="form-group">
                            <label for="branch_title">{{ __('messages.branch_title') }}</label>
                            <input type="text" class="form-control" name="branch_title" id="branch_title"
                                aria-describedby="branchTitleHelp" placeholder="Branch Title">
                        </div>
                        <div class="form-group">
                            <label for="branch_long">{{ __('Longitude') }}</label>
                            <input type="text" class="form-control" name="branch_long" id="branch_long"
                                aria-describedby="branchLongHelp" placeholder="Longitude">
                        </div>
                        <div class="form-group">
                            <label for="branch_lat">{{ __('Latitude') }}</label>
                            <input type="text" class="form-control" name="branch_lat" id="branch_lat"
                                aria-describedby="branchLatHelp" placeholder="Latitude">
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
