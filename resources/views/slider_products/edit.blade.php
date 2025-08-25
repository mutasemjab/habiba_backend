@extends('layouts.main')

@section('title')
    {{ __('messages.slider_products.edit_slider_image') }}
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
                    <h6 class="m-0 font-weight-bold text-primary">{{ __('messages.edit_slider_image') }}</h6>
                    <a href="{{ route('slider_products.index') }}" class="btn btn-primary">{{ __('messages.back') }}</a>
                </div>
                <div class="card-body">
                    @include('layouts.errors')
                    <form action="{{ route('slider_products.update', $sliderImage->id) }}" method="POST"
                        enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="form-group col-lg-6 col-md-6 col-sm-12">
                                <label for="slider_image">{{ __('messages.slider_products.image') }}</label>
                                <input type="file" class="form-control-file" name="image" id="slider_image">
                                <!-- Show current image -->
                                @if ($sliderImage->image)
                                    <div class="mt-2">
                                        <img src="{{ url('slider/images/' . $sliderImage->image) }}"
                                            alt="Current Slider Image" class="img-thumbnail" style="max-width: 200px;">
                                        <p>{{ __('messages.current_image') }}</p>
                                    </div>
                                @endif
                            </div>
                        </div>
                        
                         <div class="form-group col-lg-4 col-md-4 col-sm-4">
                                <select class="form-control" name="sub_category">
                                    <option selected disabled hidden>{{ __('messages.sub_category_name') }}</option>
                                    @foreach ($products as $product)
                                        <option value="{{ $product->id }}"
                                            @if ($product->id == $sliderImage->sub_category_id) selected @endif>
                                            {{ $product->ar_sub_category_name	 }}
                                        </option>
                                    @endforeach
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
