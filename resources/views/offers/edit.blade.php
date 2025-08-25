@extends('layouts.main')

@section('title')
    {{ __('Edit Product Offer') }}
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
                    <h6 class="m-0 font-weight-bold text-primary">{{ __('Edit Product Offer') }}</h6>
                    <a href="{{ route('offers.index') }}" class="btn btn-primary">{{ __('Back') }}</a>
                </div>
                <div class="card-body">
                    @include('layouts.errors')

                    {{-- Update the action to call the update route --}}
                    <form action="{{ route('offers.update', $offer->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <div class="row">
                            <div class="form-group col-lg-6 col-md-6 col-sm-12">
                                <label for="product_id">{{ __('Product Name') }}</label>
                                <select class="form-control" name="product_id">
                                    <option selected disabled hidden>{{ __('Select Product') }}</option>
                                    @foreach ($products as $product)
                                        <option value="{{ $product->id }}"
                                            @if ($product->id == $offer->product_id) selected @endif>
                                            {{ $product->product_name }}
                                        </option>
                                    @endforeach
                                </select>
                                <small
                                    class="text-danger">{{ __('These are the products which don\'t have an active offer') }}</small>
                            </div>

                            <div class="form-group col-lg-6 col-md-6 col-sm-12">
                                <label for="persentage">{{ __('Offer Percentage') }}</label>
                                <input type="number" class="form-control" name="persentage" id="persentage"
                                    aria-describedby="persentageHelp" placeholder="Offer Percentage"
                                    value="{{ old('persentage', $offer->persentage) }}">
                            </div>
                        </div>

                        <hr>

                        <div class="row">
                            <div class="form-group col-lg-4 col-md-4 col-sm-12">
                                <label for="start_at">{{ __('Offer Start At') }}</label>
                                <input type="date" class="form-control" name="start_at" id="start_at"
                                    aria-describedby="start_atHelp" value="{{ old('start_at', $offer->start_at) }}">
                            </div>
                            <div class="form-group col-lg-4 col-md-4 col-sm-12">
                                <label for="end_at">{{ __('Offer End At') }}</label>
                                <input type="date" class="form-control" name="end_at" id="end_at"
                                    aria-describedby="end_atHelp" value="{{ old('end_at', $offer->end_at) }}">
                            </div>
                            <div class="form-group col-lg-4 col-md-4 col-sm-12">
                                <label for="status">{{ __('Status') }}</label>
                                <select class="form-control" name="status">
                                    <option selected disabled hidden>{{ __('Select Status') }}</option>
                                    <option value="1" @if ($offer->status == 1) selected @endif>
                                        {{ __('Active') }}</option>
                                    <option value="0" @if ($offer->status == 0) selected @endif>
                                        {{ __('Inactive') }}</option>
                                </select>
                            </div>
                        </div>
                        <hr>
                        <div class="row text-center">
                            <button type="submit" class="btn btn-primary m-auto">{{ __('Update') }}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
