@extends('layouts.main')
@section('navbar')
    @include('layouts.navbar')
@endsection
@section('sidebar')
    @include('layouts.sidebar')
@endsection
@section('title')
    {{ __('messages.users') }}
@endsection
@section('content')
    <!-- Page Heading -->
    <div class="row">
        <div class="col-lg-12 mb-4">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex justify-content-between align-items-center">
                    <h6 class="m-0 font-weight-bold text-primary">{{ __('messages.users_list') }}</h6>
                    @can('users_create')
                        <a href="{{ route('users.create') }}" class="btn btn-primary">{{ __('messages.create_new_user') }}</a>
                    @endcan
                </div>

                <div class="card-body">
                    <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>{{ __('messages.name') }}</th>
                                <th>{{ __('messages.email') }}</th>
                                <th>{{ __('messages.status') }}</th>
                                <th>{{ __('messages.role') }}</th>
                                <th>{{ __('messages.actions') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($users as $user)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $user->name }}</td>
                                    <td>{{ $user->email }}</td>
                                    <td>
                                        @if ($user->status == 1)
                                            <span
                                                class="badge bg-success p-2 text-white">{{ __('messages.active') }}</span>
                                        @else
                                            <span
                                                class="badge bg-danger p-2 text-white">{{ __('messages.disabled') }}</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if (!empty($user->getRoleNames()))
                                            @foreach ($user->getRoleNames() as $v)
                                                <label class="badge bg-success p-2 text-white">{{ $v }}</label>
                                            @endforeach
                                        @endif
                                    </td>
                                    <td>
                                        @if ($user->status == 1)
                                            <form action="{{ route('users.disable', $user->id) }}" method="POST"
                                                class="d-inline">
                                                @csrf
                                                @method('POST')
                                                <button type="submit" class="btn btn-danger btn-sm"
                                                    onclick="return confirm('Are you sure?')"><i
                                                        class="fa-solid fa-circle-xmark"></i></button>
                                            </form>
                                        @else
                                            <form action="{{ route('users.activate', $user->id) }}" method="POST"
                                                class="d-inline">
                                                @csrf
                                                @method('POST')
                                                <button type="submit" class="btn btn-success btn-sm"
                                                    onclick="return confirm('Are you sure?')"><i
                                                        class="fa-solid fa-check"></i></button>
                                            </form>
                                        @endif
                                        <a href="{{ route('users.edit', $user->id) }}" class="btn btn-primary btn-sm"><i
                                                class="fa-solid fa-pen-to-square"></i></a>
                                        <form action="{{ route('users.destroy', $user->id) }}" method="POST"
                                            class="d-inline">
                                            @csrf
                                            @method('POST')
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
