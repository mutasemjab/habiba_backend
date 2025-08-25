@extends('layouts.main')

@section('title')
    {{ __('messages.create_offer') }}
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
                    <h6 class="m-0 font-weight-bold text-primary">{{ __('messages.create_offer') }}</h6>
                    <a href="{{ route('offers.index') }}" class="btn btn-primary">{{ __('messages.back') }}</a>
                </div>
                <div class="card-body">
                    @include('layouts.errors')
                    <form action="{{ route('offers.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="form-group col-lg-6 col-md-6 col-sm-12">
                                <label for="product_id">{{ __('messages.product_name') }}</label>
                                {{-- <input type="text" class="form-control" name="product_id" id="product_id"
                                    aria-describedby="product_idHelp" placeholder="Product"> --}}
                                <select class="form-control" name="product_id">
                                    <option selected disabled hidden>{{ __('messages.product_name') }}</option>
                                    @foreach ($products as $product)
                                        <option value="{{ $product->id }}">{{ $product->product_name }}</option>
                                    @endforeach
                                </select>
                                <small
                                    class="text-danger">{{ __('These are the products wich does\'nt have an active offer') }}</small>
                            </div>
                            <div class="form-group col-lg-6 col-md-6 col-sm-12">
                                <label for="persentage">{{ __('messages.persentage') }}</label>
                                <input type="number" class="form-control" name="persentage" id="persentage"
                                    aria-describedby="persentageHelp" placeholder="{{ __('messages.persentage') }}">
                            </div>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="form-group col-lg-4 col-md-4 col-sm-12">
                                <label for="start_at">{{ __('messages.starts_at') }}</label>
                                <input type="date" class="form-control" name="start_at" id="start_at"
                                    aria-describedby="start_atHelp" placeholder="{{ __('messages.starts_at') }}">
                            </div>
                            <div class="form-group col-lg-4 col-md-4 col-sm-12">
                                <label for="end_at">{{ __('messages.ends_at') }}</label>
                                <input type="date" class="form-control" name="end_at" id="end_at"
                                    aria-describedby="end_atHelp" placeholder="Offer End At">
                            </div>
                            <div class="form-group col-lg-4 col-md-4 col-sm-12">
                                <label for="end_at">{{ __('messages.offer_status') }}</label>
                                <select class="form-control" name="status">
                                    <option selected disabled hidden>{{ __('messages.offer_status') }}</option>
                                    <option value="1">{{ __('messages.active') }}</option>
                                    <option value="0">{{ __('messages.inactive') }}</option>
                                </select>
                            </div>
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
