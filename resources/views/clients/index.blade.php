@extends('layouts.main')
@section('navbar')
    @include('layouts.navbar')
@endsection
@section('sidebar')
    @include('layouts.sidebar')
@endsection
@section('title')
    {{ __('messages.clients') }}
@endsection
@section('content')
    <!-- Page Heading -->
    <div class="row">
        <div class="col-lg-12 mb-4">
            <div class="card shadow mb-4">
                <div class="card-body">
                    <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>{{ __('messages.name') }}</th>
                                <th>{{ __('messages.email') }}</th>
                                <th>{{ __('messages.mobile') }}</th>
                                <th>{{ __('messages.status') }}</th>
                                <th>{{ __('messages.actions') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($clients as $client)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $client->name }}</td>
                                    <td>{{ $client->email }}</td>
                                    <td>{{ $client->mobile }}</td>
                                    <td>
                                        @if ($client->status == 1)
                                            <span class="badge bg-success p-2 text-white">{{ __('messages.active') }}</span>
                                        @else
                                            <span class="badge bg-danger p-2 text-white">{{ __('messages.disabled') }}</span>
                                        @endif
                                    </td>
                                    <td>
                                    @can('clients_delete')
                                        @if ($client->status == 1)
                                            <form action="{{ route('clients.disable', $client->id) }}" method="POST"
                                                class="d-inline">
                                                @csrf
                                                @method('POST')
                                                <button type="submit" class="btn btn-danger btn-sm"
                                                    onclick="return confirm('Are you sure?')"><i
                                                        class="fa-solid fa-circle-xmark"></i></button>
                                            </form>
                                        @else
                                            <form action="{{ route('clients.activate', $client->id) }}" method="POST"
                                                class="d-inline">
                                                @csrf
                                                @method('POST')
                                                <button type="submit" class="btn btn-success btn-sm"
                                                    onclick="return confirm('Are you sure?')"><i
                                                        class="fa-solid fa-check"></i></button>
                                            </form>
                                        @endif
                                    @endcan
                                        {{-- <a href="{{ route('clients.edit', $client->id) }}"
                                            class="btn btn-primary btn-sm"><i class="fa-solid fa-pen-to-square"></i></a> --}}
                                        {{-- <form action="{{ route('clients.destroy', $client->id) }}" method="POST"
                                            class="d-inline">
                                            @csrf
                                            @method('POST')
                                            <button type="submit" class="btn btn-danger btn-sm"
                                                onclick="return confirm('Are you sure?')"><i
                                                    class="fa-solid fa-trash"></i></button>
                                        </form> --}}
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
