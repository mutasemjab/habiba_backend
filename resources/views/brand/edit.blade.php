@extends('layouts.main')

@section('title')
    {{ __('messages.edit_brand') }}
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
                    <h6 class="m-0 font-weight-bold text-primary">{{ __('Edit Brand') }}</h6>
                    <a href="{{ route('brands.index') }}" class="btn btn-primary">{{ __('Back') }}</a>
                </div>
                <div class="card-body">
                    @include('layouts.errors')

                    <!-- Form to update brand -->
                    <form action="{{ route('brands.update', $brand->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <!-- Brand Name Input -->
                        <div class="form-group">
                            <label for="brand_name">{{ __('messages.ar_brand_name') }}</label>
                            <input type="text" class="form-control" name="ar_brand_name" id="brand_name"
                                aria-describedby="brand_nameHelp" value="{{ $brand->ar_brand_name }}"
                                placeholder=">{{ __('messages.ar_brand_name') }}">
                        </div>
                        <div class="form-group">
                            <label for="brand_name">{{ __('messages.brand_name') }}</label>
                            <input type="text" class="form-control" name="brand_name" id="brand_name"
                                aria-describedby="brand_nameHelp" value="{{ $brand->getEnName() }}"
                                placeholder="{{ __('messages.brand_name') }}">
                        </div>

                        <!-- Current Image (Thumbnail) -->
                        <div class="form-group">
                            <label for="currentImage">{{ __('messages.current.brand.image') }}</label><br>
                            @if ($brand->image)
                                <img src="{{ asset('brands/images/' . $brand->image) }}" alt="Brand Image"
                                    class="img-thumbnail" style="width: 150px;">
                            @else
                                <p>{{ __('No image uploaded') }}</p>
                            @endif
                        </div>

                        <!-- New Image Input -->
                        <div class="form-group">
                            <label for="exampleFormControlFile1">{{ __('messages.new_brand_image') }}</label>
                            <input type="file" class="form-control-file" name="image" id="exampleFormControlFile1">
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
