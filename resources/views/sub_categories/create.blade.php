@extends('layouts.main')

@section('title')
    {{ __('New Sub Category') }}
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
                    <h6 class="m-0 font-weight-bold text-primary">{{ __('messages.new_sub_category') }}</h6>
                    <a href="{{ route('sub_categories.index') }}" class="btn btn-primary">{{ __('messages.back') }}</a>
                </div>
                <div class="card-body">
                    @include('layouts.errors')
                    <form action="{{ route('sub_categories.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="form-group">
                            <label for="sub_categories">{{ __('messages.sub_sategory_name') }}</label>
                            <input type="text" class="form-control" name="sub_category_name" id="sub_categories"
                                aria-describedby="sub_categoriesHelp" placeholder="Sub Category Name">
                        </div>
                        <div class="form-group">
                            <label for="sub_categories">{{ __('messages.ar_sub_sategory_name') }}</label>
                            <input type="text" class="form-control" name="ar_sub_category_name" id="sub_categories"
                                aria-describedby="sub_categoriesHelp" placeholder="Sub Category Name">
                        </div>
                        <div class="form-group">
                            <label for="sub_categories">{{ __('messages.select_main_category') }}</label>
                            <select name="category_id" id="category_id" class="form-control">
                                <option value="" selected disabled hidden>{{__('messages.select_main_category')}}</option>
                                @foreach (App\Models\Category::all() as $cat)
                                    <option class="" value="{{ $cat->id }}">{{ $cat->category_name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="CateImageControlFile">{{ __('messages.category_image') }}</label>
                            <input type="file" class="form-control-file" name="image" id="CateImageControlFile">
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
