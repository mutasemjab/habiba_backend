@extends('layouts.main')
@section('navbar')
    @include('layouts.navbar')
@endsection
@section('sidebar')
    @include('layouts.sidebar')
@endsection
@section('title')
    {{ __('messages.roles') }}
@endsection
@section('content')
<div class="row">
        <div class="col-lg-12 mb-4">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex justify-content-between align-items-center">
                    <h6 class="m-0 font-weight-bold text-primary">{{ __('messages.roles_list') }}</h6>
                    @can('roles_create')
                        <a href="{{ route('roles.create') }}" class="btn btn-primary">{{ __('messages.create_new_role') }}</a>
                    @endcan
                </div>

                <div class="card-body">
                    <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>{{ __('messages.role') }}</th>
                                <th>{{ __('messages.actions') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($roles as $role)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $role->name }}</td>
                                    <td>
                                        @can('roles_edit')
                                            <a href="{{ route('roles.edit', $role->id) }}"
                                                class="btn btn-primary btn-sm">{{ __('messages.edit') }}</a>
                                        @endcan
                                        @can('roles_delete')
                                            <form action="{{ route('roles.destroy', $role->id) }}" method="POST"
                                                class="d-inline">
                                                @csrf
                                                @method('POST')
                                                <button type="submit" class="btn btn-danger btn-sm"
                                                    onclick="return confirm('Are you sure?')">{{ __('messages.delete') }}</button>
                                            </form>
                                        @endcan
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
