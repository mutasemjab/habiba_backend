@extends('layouts.main')

@section('title')
    {{ __('messages.edit_sub_category') }}
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
                    <h6 class="m-0 font-weight-bold text-primary">{{ __('messages.edit_sub_category') }}</h6>
                    <a href="{{ route('sub_categories.index') }}" class="btn btn-primary">{{ __('messages.back') }}</a>
                </div>
                <div class="card-body">
                    @include('layouts.errors')
                    <form action="{{ route('sub_categories.update', $subCategory->id) }}" method="POST"
                        enctype="multipart/form-data">
                        @csrf
                        <div class="form-group mb-2">
                            <label for="sub_categories">{{ __('messages.sub_category_name') }}</label>
                            <input type="text" class="form-control" name="sub_category_name" id="sub_categories"
                                aria-describedby="sub_categoriesHelp" placeholder="Sub Category Name"
                                value="{{ $subCategory->getEnName() }}">
                        </div>
                        <div class="form-group mb-2">
                            <label for="sub_categories">{{ __('messages.ar_sub_category_name') }}</label>
                            <input type="text" class="form-control" name="ar_sub_category_name" id="sub_categories"
                                aria-describedby="ar_sub_categoriesHelp" placeholder="ar_Sub Category Name"
                                value="{{ $subCategory->ar_sub_category_name }}">
                        </div>
                        <div class="form-group mb-2">
                            <label for="sub_categories">{{ __('messages.main_category_name') }}</label>
                            <select name="category_id" id="category_id" class="form-control">
                                <option value="" selected disabled hidden>{{ __('messages.main_category_name') }}
                                </option>
                                @foreach (App\Models\Category::all() as $cat)
                                    <option class="" value="{{ $cat->id }}"
                                        @if ($cat->id == $subCategory->category_id) selected @endif>{{ $cat->category_name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <!-- Current Image (Thumbnail) -->
                        <div class="form-group mb-2">
                            <label for="currentImage">{{ __('messages.current_category_image') }}</label><br>
                            @if ($subCategory->image)
                                <img src="{{ asset('sub_categories/images/' . $subCategory->image) }}"
                                    alt="Sub Category Image" class="img-thumbnail" style="width: 150px;">
                            @else
                                <p>{{ __('No image uploaded') }}</p>
                            @endif
                        </div>
                        <!-- New Image Input -->
                        <div class="form-group mb-2">
                            <label for="exampleFormControlFile1">{{ __('messages.upload_new_image') }}</label>
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
