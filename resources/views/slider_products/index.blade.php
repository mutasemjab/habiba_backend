@extends('layouts.main')

@section('navbar')
    @include('layouts.navbar')
@endsection

@section('sidebar')
    @include('layouts.sidebar')
@endsection

@section('title')
    {{ __('messages.slider_products') }}
@endsection

@section('content')
    <!-- Page Heading -->
    <div class="row">
        <div class="col-lg-12 mb-4">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex justify-content-between align-items-center">
                    <h6 class="m-0 font-weight-bold text-primary">{{ __('messages.slider_products_list') }}</h6>
                    <a href="{{ route('slider_products.create') }}"
                        class="btn btn-primary">{{ __('messages.new_slider_image') }}</a>
                </div>

                <div class="card-body">
                    <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>{{ __('messages.image') }}</th>
                                <th>{{ __('messages.actions') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($sliderProducts as $sliderImage)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>
                                        <img src="{{ asset('slider/images/' . $sliderImage->image) }}" alt="Slider Image"
                                            width="100">
                                    </td>
                                    <td>
                                        <a href="{{ route('slider_products.edit', $sliderImage->id) }}"
                                            class="btn btn-primary btn-sm">{{ __('messages.edit') }}</a>
                                        <form action="{{ route('slider_products.destroy', $sliderImage->id) }}"
                                            method="POST" class="d-inline">
                                            @csrf
                                            <button type="submit" class="btn btn-danger btn-sm"
                                                onclick="return confirm('{{ __('messages.slider_image_confirm_delete') }}')">
                                                {{ __('messages.delete') }}
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        $(document).ready(function() {
            $('#dataTable').DataTable();
        });
    </script>
@endsection
