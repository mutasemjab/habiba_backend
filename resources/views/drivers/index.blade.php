@extends('layouts.main')

@section('navbar')
    @include('layouts.navbar')
@endsection

@section('sidebar')
    @include('layouts.sidebar')
@endsection

@section('title')
    {{ __('messages.drivers_list') }}
@endsection

@section('content')
    <!-- Page Heading -->
    <div class="row">
        <div class="col-lg-12 mb-4">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex justify-content-between align-items-center">
                    <h6 class="m-0 font-weight-bold text-primary">{{ __('messages.drivers_list') }}</h6>
                    <a href="{{ route('drivers.create') }}" class="btn btn-primary">{{ __('messages.create_new_driver') }}</a>
                </div>

                <div class="card-body">
                    <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>{{ __('messages.avatar') }}</th> <!-- New Avatar Column -->
                                <th>{{ __('messages.name') }}</th>
                                <th>{{ __('messages.username') }}</th>
                                <th>{{ __('messages.nid') }}</th>
                                <th>{{ __('messages.vehicle_number') }}</th>
                                <th>{{ __('messages.vehicle_color') }}</th>
                                <th>{{ __('messages.wallet_balance') }}</th>
                                <th>{{ __('messages.status') }}</th>
                                <th>{{ __('messages.actions') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($drivers as $driver)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>
                                        @if ($driver->image)
                                            <img src="{{ asset('storage/' . $driver->image) }}" alt="{{ $driver->name }}"
                                                class="rounded-circle"
                                                style="width: 50px; height: 50px; object-fit: cover;">
                                        @else
                                            <img src="{{ asset('images/default-avatar.png') }}" alt="Default Avatar"
                                                class="rounded-circle"
                                                style="width: 50px; height: 50px; object-fit: cover;">
                                        @endif
                                    </td>
                                    <td>{{ $driver->name }}</td>
                                    <td>{{ $driver->username }}</td>
                                    <td>{{ $driver->nid }}</td>
                                    <td>{{ $driver->vehichle_number }}</td>
                                    <td>{{ $driver->vehichle_color }}</td>
                                    <td>{{ $driver->wallet }}</td>
                                    <td>
                                        @if ($driver->status == 1)
                                            <span
                                                class="badge bg-primary p-2 text-white">{{ __('messages.active') }}</span>
                                        @else
                                            <span
                                                class="badge bg-danger p-2 text-white">{{ __('messages.disabled') }}</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if ($driver->status == 1)
                                            <form action="{{ route('drivers.toggle', $driver->id) }}" method="POST"
                                                class="d-inline">
                                                @csrf
                                                @method('POST')
                                                <button type="submit" class="btn btn-danger btn-sm"
                                                    title="{{ __('messages.disable') }}"
                                                    onclick="return confirm('{{ __('messages.confirm_disable') }}')"><i
                                                        class="fa-solid fa-circle-xmark"></i></button>
                                            </form>
                                        @else
                                            <form action="{{ route('drivers.toggle', $driver->id) }}" method="POST"
                                                class="d-inline">
                                                @csrf
                                                @method('POST')
                                                <button type="submit" class="btn btn-success btn-sm"
                                                    title="{{ __('messages.activate') }}"
                                                    onclick="return confirm('{{ __('messages.confirm_activate') }}')"><i
                                                        class="fa-solid fa-check"></i></button>
                                            </form>
                                        @endif
                                        <a href="{{ route('drivers.edit', $driver->id) }}"
                                            title="{{ __('messages.edit_driver') }}" class="btn btn-primary btn-sm"><i
                                                class="fa-solid fa-pen-to-square"></i></a>
                                        {{-- Destroy form --}}
                                        <form action="{{ route('drivers.destroy', $driver->id) }}" method="POST"
                                            class="d-inline">
                                            @csrf
                                            @method('POST')
                                            <button type="submit" class="btn btn-danger btn-sm"
                                                onclick="return confirm('{{ __('messages.confirm_delete') }}')"><i
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
