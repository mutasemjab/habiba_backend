@extends('layouts.main')

@section('title')
    {{ __('messages.edit_category') }}
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
                    <h6 class="m-0 font-weight-bold text-primary">{{ __('messages.edit_category') }}</h6>
                    <a href="{{ route('categories.index') }}" class="btn btn-primary">{{ __('messages.back') }}</a>
                </div>
                <div class="card-body">
                    @include('layouts.errors')
                     
                    <!-- Form to update brand -->
                    <form action="{{ route('categories.update', $category->id) }}" method="POST"
                        enctype="multipart/form-data">
                        @csrf
                         
                        <!-- Brand Name Input -->
                        <div class="form-group mb-4">
                            <label for="ar_category_name">{{ __('messages.ar_category_name') }}</label>
                            <input type="text" class="form-control" name="ar_category_name" id="ar_category_name"
                                aria-describedby="ar_category_nameHelp" value="{{ $category->ar_category_name }}"
                                placeholder="{{ __('messages.ar_category_name') }}">
                        </div>
                        <div class="form-group mb-4">
                            <label for="category_name">{{ __('messages.category_name') }}</label>
                            <input type="text" class="form-control" name="category_name" id="category_name"
                                aria-describedby="brand_nameHelp" value="{{ $category->getEnName() }}"
                                placeholder="{{ __('messages.category_name') }}">
                        </div>
                                           
                        <div class="form-group mb-4">
                            <label for="category_name">{{ __('messages.order') }}</label>
                            <input type="text" class="form-control" name="order" id="order"
                                aria-describedby="orderHelp" value="{{ $category->order }}"
                                placeholder="{{ __('messages.order') }}">
                        </div>

                        <!-- Current Image (Thumbnail) -->
                        <div class="form-group mb-4">
                            <label for="currentImage">{{ __('messages.current_category_image') }}</label><br>
                            @if ($category->image)
                                <img src="{{ asset('categories/images/' . $category->image) }}" alt="Brand Image"
                                    class="img-thumbnail" style="width: 150px;">
                            @else
                                <p>{{ __('messages.no_new_images') }}</p>
                            @endif
                        </div>

                        <!-- New Image Input -->
                        <div class="form-group mb-4">
                            <label for="exampleFormControlFile1">{{ __('messages.upload_new_category_image') }}</label>
                            <input type="file" class="form-control-file" name="image" id="exampleFormControlFile1">
                        </div>

                        <div class="form-group mb-4">
                            <label for="is_it_smoke">{{ __('messages.is_it_smoke') }}</label>
                            <select class="form-control" name="is_it_smoke" id="is_it_smoke">
                                <option value="">{{ __('messages.select_smoke_option') }}</option>
                                <option value="1" {{ $category->is_it_smoke == 1 ? 'selected' : '' }}>{{ __('messages.smoke_yes') }}</option>
                                <option value="2" {{ $category->is_it_smoke == 2 ? 'selected' : '' }}>{{ __('messages.smoke_no') }}</option>
                            </select>
                        </div>
                        
                        <hr>
                        <!-- Submit Button -->
                        <div class="row text-center">
                            <button type="submit" class="btn btn-primary m-auto">{{ __('messages.submit') }}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
