@extends('layouts.main')

@section('title')
    {{ __('messages.product.new_product') }}
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
                    <h6 class="m-0 font-weight-bold text-primary">{{ __('messages.product.new_product') }}</h6>
                    <a href="{{ route('products.index') }}" class="btn btn-primary">{{ __('messages.back') }}</a>
                </div>
                <div class="card-body">
                    @include('layouts.errors')
                    <form action="{{ route('products.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="form-group col-lg-6 col-md-6 col-sm-12">
                                <label for="ar_product_name">{{ __('messages.ar_product_name') }}</label>
                                <input type="text" class="form-control" name="ar_product_name" id="ar_product_name"
                                    aria-describedby="product_idHelp" placeholder="{{ __('messages.ar_product_name') }}">
                            </div>
                            <div class="form-group col-lg-6 col-md-6 col-sm-12">
                                <label for="product_name">{{ __('messages.product.name') }}</label>
                                <input type="text" class="form-control" name="product_name" id="product_name"
                                    aria-describedby="product_idHelp"
                                    placeholder="{{ __('messages.product.name_placeholder') }}">
                            </div>
                            <div class="form-group col-lg-6 col-md-6 col-sm-12">
                                <label for="brand_id">{{ __('messages.brand_name') }}</label>
                                <select name="brand_id" id="brand_id" class="form-control">
                                    <option value="" selected disabled hidden>{{ __('messages.select_brand') }}
                                    </option>
                                    @foreach (App\Models\Brand::all() as $brand)
                                        <option class="" value="{{ $brand->id }}">{{ $brand->brand_name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="form-group col-lg-4 col-md-4 col-sm-4">
                                <label for="category_id">{{ __('messages.main_category_name') }}</label>
                                <select name="category_id" id="category_id" class="form-control">
                                    <option value="" selected disabled hidden>
                                        {{ __('messages.select_main_category') }}</option>
                                    @foreach (App\Models\Category::all() as $cat)
                                        <option class="" value="{{ $cat->id }}">{{ $cat->category_name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group col-lg-4 col-md-4 col-sm-4">
                                <label for="sub_category_id">{{ __('messages.sub_category_name') }}</label>
                                <select name="sub_category_id" id="sub_category_id" class="form-control">
                                    <option value="" selected disabled hidden>
                                        {{ __('messages.select_sub_category') }}</option>
                                    @foreach (App\Models\SubCategory::all() as $Subcat)
                                        <option class="" value="{{ $Subcat->id }}">
                                            {{ $Subcat->sub_category_name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group col-lg-4 col-md-4 col-sm-4">
                                <label for="price">{{ __('messages.price') }}</label>
                                <input type="number" name="price" id="price" class="form-control"
                                    placeholder="{{ __('messages.price') }}" step="0.01" min="0">
                            </div>

                        </div>
                        <hr>
                        <div class="row">
                            <div class="form-group col-lg-6 col-md-6 col-sm-6">
                                <label for="product_unit">{{ __('messages.product.unit') }}</label>
                                <select name="product_unit" id="product_unit" class="form-control">
                                    <option value="" selected disabled hidden>{{ __('messages.select_unit') }}
                                    </option>
                                    <option value="kg">{{ __('messages.unit.kg') }}</option>
                                    <option value="Liter">{{ __('messages.unit.liter') }}</option>
                                    <option value="Box">{{ __('messages.unit.box') }}</option>
                                </select>
                            </div>
                            <div class="form-group col-lg-6 col-md-6 col-sm-6">
                                <label for="product_status">{{ __('messages.product.status') }}</label>
                                <select name="product_status" id="product_status" class="form-control">
                                    <option value="" selected disabled hidden>{{ __('messages.select_status') }}
                                    </option>
                                    <option value="1">{{ __('messages.active') }}</option>
                                    <option value="0">{{ __('messages.inactive') }}</option>
                                </select>
                            </div>
                        </div>
                        <hr>
                        <div class="row p-2">
                            <textarea name="product_description" id="product_description" rows="10" class="form-control w-100"></textarea>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="form-group col-lg-4 col-md-4 col-sm-4">
                                <label for="CateImageControlFile">{{ __('messages.product.image') }}</label>
                                <input type="file" class="form-control-file" name="image" id="CateImageControlFile">
                            </div>
                            <div class="form-group col-lg-4 col-md-4 col-sm-4">
                                <label for="CateGalleryControlFile">{{ __('messages.product.gallery') }}</label>
                                <input type="file" class="form-control-file" name="gallary[]" multiple
                                    id="CateGalleryControlFile">
                            </div>
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
@section('scripts')
 
    <script>
        $(document).ready(function() {
            // Initialize Select2 on category and subcategory dropdowns
            $('#category_id').select2({
                placeholder: "{{ __('messages.select_main_category') }}",
                allowClear: true
            });
            
            $('#sub_category_id').select2({
                placeholder: "{{ __('messages.select_sub_category') }}",
                allowClear: true
            });
            
            // Optional: Also apply Select2 to other dropdowns
            $('#brand_id').select2({
                placeholder: "{{ __('messages.select_brand') }}",
                allowClear: true
            });
            
            $('#product_unit').select2({
                placeholder: "{{ __('messages.select_unit') }}",
                allowClear: true
            });
            
            $('#product_status').select2({
                placeholder: "{{ __('messages.select_status') }}",
                allowClear: true
            });
            
            // Optional: Make subcategory dependent on category selection
         $('#category_id').on('change', function() {
            var categoryId = $(this).val();
            if(categoryId) {
                $.ajax({
                    url: "{{ route('get.subcategories', '') }}/" + categoryId,
                    type: 'GET',
                    dataType: 'json',
                    success: function(data) {
                        $('#sub_category_id').empty();
                        $('#sub_category_id').append('<option value="" selected disabled hidden>{{ __("messages.select_sub_category") }}</option>');
                        $.each(data, function(key, value) {
                            $('#sub_category_id').append('<option value="' + value.id + '">' + value.sub_category_name + '</option>');
                        });
                        $('#sub_category_id').trigger('change');
                    }
                });
            } else {
                $('#sub_category_id').empty();
                $('#sub_category_id').append('<option value="" selected disabled hidden>{{ __("messages.select_sub_category") }}</option>');
                $('#sub_category_id').trigger('change');
            }
        });
        });
    </script>
@endsection
