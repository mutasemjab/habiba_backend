@extends('layouts.main')

@section('title', __('messages.assign_driver_and_branch'))

@section('navbar')
    @include('layouts.navbar')
@endsection

@section('sidebar')
    @include('layouts.sidebar')
@endsection

@section('content')
    <div class="container">
        <h1 class="mt-4">{{ __('messages.assign_driver_and_branch') }} #{{ $order->id }}</h1>
        <div class="card">
            {{-- <div class="card-body">
                <p><strong>{{ __('messages.user_name') }}:</strong> {{ $order->client->name }}</p>
                <p><strong>{{ __('messages.current_driver') }}:</strong>
                    {{ $order->driver ? $order->driver->name : __('messages.not_assigned') }}</p>
                <p><strong>{{ __('messages.current_branch') }}:</strong>
                    {{ $order->branch ? $order->branch->branch_title : __('messages.not_assigned') }}</p>
                <p><strong>{{ __('messages.order_status') }}:</strong> {{ __('messages.'.$order->status) }}</p>
            </div> --}}
            <table class="table table-border">
                <thead>
                    <tr class='text-center'>
                        <th class='text-center'><strong>{{ __('messages.user_name') }}</strong></th>
                        <th class='text-center'><strong>{{ __('messages.current_driver') }}</strong></th>
                        <th class='text-center'><strong>{{ __('messages.current_branch') }}</strong></th>
                        <th class='text-center'><strong>{{ __('messages.order_status') }}</strong></th>
                    </tr>
                </thead>
                <tbody class='text-center'>
                    <tr class='text-center'>
                        <td class='text-center'>{{ $order->client->name }}</td>
                        <td class='text-center'>{{ $order->driver ? $order->driver->name : __('messages.not_assigned') }}</td>
                        <td class='text-center'>{{ $order->branch ? $order->branch->branch_title : __('messages.not_assigned') }}</td>
                        <td class='text-center'>{{ __('messages.'.$order->status) }}</td>
                    </tr>
                </tbody>
            </table>
        </div>

        <h3 class="mt-4">{{ __('messages.assign_driver_and_branch') }} </h3>
        <form action="{{ route('orders.assign.driver', $order->id) }}" method="POST">
            @csrf
            <!-- Driver Selection -->
            <div class="form-group">
                <label for="driver">{{ __('messages.select_driver') }}:</label>
                <select name="driver_id" id="driver" class="form-control" required>
                    <option value="" disabled selected>{{ __('messages.select_driver') }}</option>
                    @foreach ($drivers as $driver)
                        <option value="{{ $driver->id }}"
                            {{ $order->driver && $order->driver->id == $driver->id ? 'selected' : '' }}>
                            {{ $driver->name }} ({{ $driver->username }})
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Branch Selection -->
            <div class="form-group mt-3">
                <label for="branch">{{ __('messages.select_branch') }}:</label>
                <select name="branch_id" id="branch" class="form-control" required>
                    <option value="" disabled selected hidden>{{ __('messages.select_branch') }}</option>
                    @foreach (App\Models\Branch::all() as $branch)
                        <option value="{{ $branch->id }}"
                            {{ $order->branch && $order->branch->id == $branch->id ? 'selected' : '' }}>
                            {{ $branch->branch_title }}
                        </option>
                    @endforeach
                </select>
            </div>

            <hr>
            <button type="submit" class="btn btn-primary">{{ __('messages.assign_driver_and_branch') }}</button>
        </form>

        <a href="{{ route('orders.index') }}" class="btn btn-secondary mt-3">{{__('messages.back')}}</a>
    </div>
@endsection
