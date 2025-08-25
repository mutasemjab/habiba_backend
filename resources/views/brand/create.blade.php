@extends('layouts.main')

@section('title')
    {{ __('messages.new_brand') }}
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
                    <h6 class="m-0 font-weight-bold text-primary">{{ __('messages.brands_list') }}</h6>
                    <a href="{{ route('brands.index') }}" class="btn btn-primary">{{ __('messages.back') }}</a>
                </div>
                <div class="card-body">
                    @include('layouts.errors')
                    <form action="{{ route('brands.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="form-group">
                            <label for="ar_brand_name">{{ __('messages.ar_brand_name') }}</label>
                            <input type="text" class="form-control" name="ar_brand_name" id="ar_brand_name"
                                aria-describedby="ar_brand_nameHelp" placeholder="{{ __('messages.ar_brand_name') }}">
                        </div>
                        <div class="form-group">
                            <label for="brand_name">{{ __('messages.brand_name') }}</label>
                            <input type="text" class="form-control" name="brand_name" id="brand_name"
                                aria-describedby="brand_nameHelp" placeholder="Brand Name">
                        </div>
                        <div class="form-group">
                            <label for="exampleFormControlFile1">{{ __('Brand Image') }}</label>
                            <input type="file" class="form-control-file" name="image" id="exampleFormControlFile1">
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
