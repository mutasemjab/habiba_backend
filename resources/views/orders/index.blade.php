@extends('layouts.main')

@section('navbar')
    @include('layouts.navbar')
@endsection

@section('sidebar')
    @include('layouts.sidebar')
@endsection

@section('title')
    {{ __('messages.orders') }}
@endsection

@section('content')
    @include('layouts.errors')
    @include('layouts.sessions')

    <div class="row">
        <div class="col-lg-12 mb-4">
            <div class="card shadow mb-4">
                <div class="card-header">
                    <ul class="nav nav-tabs" id="orderTabs" role="tablist">
                        @foreach ($statuses as $status)
                            <li class="nav-item" role="presentation">
                                <button class="nav-link {{ $loop->first ? 'active' : '' }}" id="tab-{{ $status }}-tab"
                                    data-bs-toggle="tab" data-bs-target="#tab-{{ $status }}" type="button"
                                    role="tab" aria-controls="tab-{{ $status }}"
                                    aria-selected="{{ $loop->first ? 'true' : 'false' }}">
                                    {{ __('messages.' . $status) }}
                                    <span class="badge bg-secondary">{{ $orders->where('status', $status)->count() }}</span>
                                </button>
                            </li>
                        @endforeach
                    </ul>
                </div>
                <div class="card-body">
                    <div class="tab-content" id="orderTabsContent">
                        @foreach ($statuses as $status)
                            <div class="tab-pane fade {{ $loop->first ? 'show active' : '' }}" id="tab-{{ $status }}"
                                role="tabpanel" aria-labelledby="tab-{{ $status }}-tab">
                                <table class="table table-bordered order-table" id="table-{{ $status }}"
                                    width="100%" cellspacing="0">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>{{ __('messages.user_name') }}</th>
                                            <th>{{ __('messages.driver_name') }}</th>
                                            <th>{{ __('messages.order_status') }}</th>
                                            <th>{{ __('messages.total_amount') }}</th>
                                            <th>{{ __('messages.created at') }}</th>
                                            <th>{{ __('messages.actions') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($orders->where('status', $status) as $order)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>{{ $order->client->name }}</td>
                                                <td>{{ $order->driver ? $order->driver->name : __('messages.not_assigned') }}
                                                </td>
                                                <td>
                                                    @php
                                                        $statusClasses = [
                                                            'pending' => 'badge bg-warning text-dark',
                                                            'pending_driver' => 'badge bg-primary text-white',
                                                            'pending_pickup' => 'badge bg-primary text-white',
                                                            'shipped' => 'badge bg-primary text-white',
                                                            'ready' => 'badge bg-success text-white',
                                                            'done' => 'badge bg-success text-white',
                                                            'canceled' => 'badge bg-danger text-white',
                                                        ];
                                                    @endphp
                                                    <span
                                                        class="{{ $statusClasses[$order->status] ?? 'badge bg-secondary text-white' }}">
                                                        {{ __('messages.' . $order->status) }}
                                                    </span>
                                                </td>
                                                <td>{{ $order->total_price }}</td>
                                                <td>{{ $order->created_at }}</td>
                                                <td>
                                                    @if (in_array($order->status, ['pending', 'pending_driver']))
                                                        <a href="{{ route('orders.assign', $order->id) }}"
                                                            class="btn btn-primary btn-sm">
                                                            {{ __('messages.assign_driver') }}
                                                        </a>
                                                    @endif

                                                    @if (!in_array($order->status, ['done', 'ready']))
                                                        <a href="{{ route('orders.show', $order->id) }}"
                                                            class="btn btn-secondary btn-sm">
                                                            {{ __('messages.change_order_status') }}
                                                        </a>
                                                    @elseif ($order->status === 'done')
                                                        <span
                                                            class="badge bg-success">{{ __('messages.order_done') }}</span>
                                                    @endif
                                                         <a href="{{ route('orders.preparation', $order->id) }}"
                                                            class="btn btn-secondary btn-sm">
                                                            {{ __('messages.Prepare order') }}
                                                        </a>
                                                   
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="6" class="text-center">{{ __('messages.no_orders') }}</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

{{-- @section('scripts')
    <script>
        $(document).ready(function() {
            $('.order-table').each(function() {
                $(this).DataTable();
            });
            $('a[data-bs-toggle="tab"]').on('shown.bs.tab', function(e) {
                $($.fn.dataTable.tables(true)).DataTable().columns.adjust();
            });
        });
    </script>
@endsection --}}
