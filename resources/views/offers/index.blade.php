@extends('layouts.main')
@section('navbar')
    @include('layouts.navbar')
@endsection
@section('sidebar')
    @include('layouts.sidebar')
@endsection
@section('title')
    {{ __('messages.offers_list') }}
@endsection
@section('content')
    <div class="row">
        <div class="col-lg-12 mb-4">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex justify-content-between align-items-center">
                    <h6 class="m-0 font-weight-bold text-primary">{{ __('messages.offers_list') }}</h6>
                    <a href="{{ route('offers.create') }}" class="btn btn-primary">{{ __('messages.create_offer') }}</a>
                </div>

                <div class="card-body">
                    <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>{{ __('messages.offer') }}</th>
                                <th>{{ __('messages.persentage') }}</th>
                                <th>{{ __('messages.starts_at') }}</th>
                                <th>{{ __('messages.ends_at') }}</th>
                                <th>{{ __('messages.offer_status') }}</th>
                                <th>{{ __('messages.actions') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($offers as $offer)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $offer->product->product_name }}</td>
                                    <td>{{ $offer->persentage }}</td>
                                    <td>{{ $offer->start_at }}</td>
                                    <td>{{ $offer->end_at }}</td>
                                    <td>
                                        @if ($offer->status == '1')
                                            <span class="badge bg-success p-2 text-white">{{ __('active') }}</span>
                                        @else
                                            <span class="badge bg-danger p-2 text-white">{{ __('disabled') }}</span>
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{ route('offers.edit', $offer->id) }}" class="btn btn-primary btn-sm"><i
                                                class="fa-solid fa-pen-to-square"></i></a>
                                        <form action="{{ route('offers.destroy', $offer->id) }}" method="POST"
                                            class="d-inline">
                                            @csrf
                                            <button type="submit" class="btn btn-danger btn-sm"
                                                onclick="return confirm('Are you sure?')"><i
                                                    class="fa-solid fa-trash"></i></button>
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
