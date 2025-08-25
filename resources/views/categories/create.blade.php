@extends('layouts.main')

@section('title')
    {{ __('messages.new_category') }}
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
                    <h6 class="m-0 font-weight-bold text-primary">{{ __('messages.new_category') }}</h6>
                    <a href="{{ route('categories.index') }}" class="btn btn-primary">{{ __('messages.back') }}</a>
                </div>
                <div class="card-body">
                    @include('layouts.errors')
                    <form action="{{ route('categories.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="form-group mb-4">
                            <label for="ar_category_name">{{ __('messages.ar_category_name') }}</label>
                            <input type="text" class="form-control" name="ar_category_name" id="ar_category_name"
                                aria-describedby="ar_category_nameHelp" placeholder="Category Name">
                        </div>
                        <div class="form-group mb-4">
                            <label for="category_name">{{ __('messages.category_name') }}</label>
                            <input type="text" class="form-control" name="category_name" id="category_name"
                                aria-describedby="category_nameHelp" placeholder="Category Name">
                        </div>
                                          
                        <div class="form-group mb-4">
                            <label for="category_name">{{ __('messages.order') }}</label>
                            <input type="text" class="form-control" name="order" id="order"
                                aria-describedby="orderHelp" placeholder="order">
                        </div>

                        <div class="form-group mb-4">
                            <label for="CateImageControlFile">{{ __('messages.category_image') }}</label>
                            <input type="file" class="form-control-file" name="image" id="CateImageControlFile">
                        </div>

                        <div class="form-group mb-4">
                            <label for="is_it_smoke">{{ __('messages.is_it_smoke') }}</label>
                            <select class="form-control" name="is_it_smoke" id="is_it_smoke">
                                <option value="">{{ __('messages.select_smoke_option') }}</option>
                                <option value="1">{{ __('messages.smoke_yes') }}</option>
                                <option value="2" selected>{{ __('messages.smoke_no') }}</option>
                            </select>
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
