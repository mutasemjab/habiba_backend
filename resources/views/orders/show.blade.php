@extends('layouts.main')

@section('title', 'messages.order_detail')

@section('navbar')
    @include('layouts.navbar')
@endsection
@section('sidebar')
    @include('layouts.sidebar')
@endsection

@section('content')
    <div class="container">
        <h1 class="mt-4">{{ __('messages.order') }} #{{ $order->id }}</h1>
        <div class="card">
            <div class="card-body">
                <p><strong>{{ __('messages.user_name') }}:</strong> {{ $order->client->name }}</p>
                <p><strong>{{ __('messages.driver_name') }}:</strong>
                    {{ $order->driver ? $order->driver->name : __('messages.not_assigned') }}</p>
                <p><strong>{{ __('messages.order_status') }}:</strong> {{ __('messages.' . $order->status) }}</p>
                <p><strong>{{ __('messages.delivery_cost') }}:</strong> JD {{ number_format($order->delivery_cost, 2) }}</p>
                <p><strong>{{ __('messages.total_cost') }}:</strong> JD {{ number_format($order->order_final_cost, 2) }}</p>
            </div>
        </div>

        <!-- Update Delivery Cost Section -->
        <div class="card mt-3">
            <div class="card-header">
                <h5>{{ __('messages.update_delivery_cost') }}</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('orders.update.delivery.cost', $order->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="form-group">
                        <label for="delivery_cost">{{ __('messages.delivery_cost') }}:</label>
                        <input type="number" step="0.01" name="delivery_cost" id="delivery_cost" 
                               class="form-control" value="{{ $order->delivery_cost }}" required>
                    </div>
                    <button type="submit" class="btn btn-info">{{ __('messages.update_delivery_cost') }}</button>
                </form>
            </div>
        </div>

        <!-- Add Product Section -->
        <div class="card mt-3">
            <div class="card-header">
                <h5>{{ __('messages.add_product_to_order') }}</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('orders.add.product', $order->id) }}" method="POST">
                    @csrf
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="product_id">{{ __('messages.select_product') }}:</label>
                                <select name="product_id" id="product_select" class="form-control" required>
                                    <option value="">{{ __('messages.search_and_select_product') }}</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="quantity">{{ __('messages.quantity') }}:</label>
                                <input type="number" name="quantity" id="quantity" class="form-control" 
                                       value="1" min="1" required>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>&nbsp;</label>
                                <button type="submit" class="btn btn-success form-control">
                                    {{ __('messages.add_product') }}
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <hr>
        <h3>{{ __('messages.order_items') }}</h3>
        <div class="card">
            <div class="card-body">
                @foreach ($order->orderItems as $item)
                    <div class="d-flex align-items-center mb-3 p-3 border rounded">
                        <div class="flex-grow-1">
                            <p class="mb-1"><strong>{{ $item->product->product_name ?? 'Product Deleted' }}</strong></p>
                            <p class="mb-1">{{ __('messages.quantity') }}: {{ $item->product_qty }}</p>
                            <p class="mb-0">{{ __('messages.price_at_time') }}: JD {{ number_format($item->price_at_time, 2) }}</p>
                            <p class="mb-0">{{ __('messages.total') }}: JD {{ number_format($item->price_at_time * $item->product_qty, 2) }}</p>
                        </div>
                        
                        @if ($item->product)
                            <img src="{{ asset('products/images/' . $item->product->image) }}" 
                                 alt="{{ $item->product->product_name }}" 
                                 class="rounded ml-3" 
                                 style="width: 60px; height: 60px; object-fit: cover;">
                        @else
                            <img src="{{ asset('images/default-avatar.png') }}" 
                                 alt="Default Avatar" 
                                 class="rounded ml-3" 
                                 style="width: 60px; height: 60px; object-fit: cover;">
                        @endif

                        <!-- Update Quantity Form -->
                        <form action="{{ route('orders.update.item', ['orderId' => $order->id, 'itemId' => $item->id]) }}" 
                              method="POST" class="ml-3">
                            @csrf
                            @method('PUT')
                            <div class="input-group" style="width: 120px;">
                                <input type="number" name="quantity" value="{{ $item->product_qty }}" 
                                       class="form-control form-control-sm" min="1" required>
                                <div class="input-group-append">
                                    <button type="submit" class="btn btn-sm btn-primary">
                                        {{ __('messages.update') }}
                                    </button>
                                </div>
                            </div>
                        </form>

                        <!-- Delete Button -->
                        <form action="{{ route('orders.delete.item', ['orderId' => $order->id, 'itemId' => $item->id]) }}" 
                              method="POST" class="ml-2">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm" 
                                    onclick="return confirm('{{ __('messages.confirm_delete') }}')">
                                {{ __('messages.delete') }}
                            </button>
                        </form>
                    </div>
                @endforeach
            </div>
        </div>

        <h3 class="mt-4">{{ __('messages.change_order_status') }}</h3>
        <form action="{{ route('orders.change.status', $order->id) }}" method="POST">
            @csrf
            <div class="form-group">
                <label for="status">{{ __('messages.select_new_status') }}:</label>
                <select name="status" id="status" class="form-control" required>
                    <option value="{{ $order->status }}" selected hidden>
                        {{ __('messages.' . $order->status) }}</option>
                    <option value="pending">{{ __('messages.pending') }}</option>
                    <option value="pending_driver">{{ __('messages.pending_driver') }}</option>
                    <option value="pending_pickup">{{ __('messages.pending_pickup') }}</option>
                    <option value="shipped">{{ __('messages.shipped') }}</option>
                    <option value="ready">{{ __('messages.ready') }}</option>
                    <option value="canceled">{{ __('messages.canceled') }}</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">{{ __('messages.change_order_status') }}</button>
        </form>

        <a href="{{ route('orders.index') }}" class="btn btn-secondary mt-3">{{ __('messages.back') }}</a>
    </div>

    <!-- Include Select2 CSS and JS -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <script>
        $(document).ready(function() {
            $('#product_select').select2({
                placeholder: '{{ __("messages.search_and_select_product") }}',
                allowClear: true,
                ajax: {
                    url: '{{ route("products.search") }}',
                    dataType: 'json',
                    delay: 250,
                    data: function (params) {
                        return {
                            q: params.term,
                            page: params.page || 1
                        };
                    },
                    processResults: function (data) {
                        return {
                            results: data.items,
                            pagination: {
                                more: data.pagination.more
                            }
                        };
                    },
                    cache: true
                },
                minimumInputLength: 2,
                templateResult: function (product) {
                    if (product.loading) {
                        return product.text;
                    }
                    return $('<span>' + product.text + ' - JD' + product.price + '</span>');
                },
                templateSelection: function (product) {
                    return product.text || product.product_name;
                }
            });
        });
    </script>
@endsection