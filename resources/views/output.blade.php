@extends('layouts.main')
@section('navbar')
    @include('layouts.navbar')
@endsection
@section('sidebar')
    @include('layouts.sidebar')
@endsection
@section('title')
    Synchronization Results
@endsection

@section('content')

@if(isset($success) && $success)
    <div class="alert alert-success">
        {{ $success }}
    </div>
@endif

@if(isset($error) && $error)
    <div class="alert alert-danger">
        {{ $error }}
    </div>
@endif

@if(isset($sync_results) && !empty($sync_results))
    @php $results = $sync_results; @endphp
    
    <!-- Log Messages Section -->
    @if(!empty($results['log_messages']))
        <div class="card mt-3">
            <div class="card-header">
                <h5><i class="fas fa-list-alt me-2"></i>Process Log</h5>
            </div>
            <div class="card-body">
                <div class="log-container" style="max-height: 400px; overflow-y: auto; background-color: #f8f9fa; padding: 15px; border-radius: 5px; font-family: 'Courier New', monospace; font-size: 14px;">
                    @foreach($results['log_messages'] as $index => $log)
                        <div class="log-entry mb-2 d-flex">
                            <span class="text-muted me-3" style="min-width: 60px;">[{{ str_pad($index + 1, 3, '0', STR_PAD_LEFT) }}]</span>
                            <span class="badge me-3
                                @if($log['level'] == 'error') bg-danger
                                @elseif($log['level'] == 'warning') bg-warning
                                @elseif($log['level'] == 'success') bg-success
                                @else bg-info
                                @endif
                                " style="min-width: 70px; text-align: center;">
                                {{ strtoupper($log['level']) }}
                            </span>
                            <span class="flex-grow-1">{{ $log['message'] }}</span>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    @endif
    
    <div class="card mt-3">
        <div class="card-header">
            <h5>Synchronization Results</h5>
        </div>
        <div class="card-body">
            <!-- Summary -->
            <div class="row mb-3">
                <div class="col-md-3">
                    <div class="card bg-info text-white">
                        <div class="card-body text-center">
                            <h4>{{ $results['total_fetched'] ?? 0 }}</h4>
                            <p>Products Fetched</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card bg-success text-white">
                        <div class="card-body text-center">
                            <h4>{{ $results['total_updated'] ?? 0 }}</h4>
                            <p>Prices Updated</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card bg-warning text-white">
                        <div class="card-body text-center">
                            <h4>{{ $results['total_not_found'] ?? 0 }}</h4>
                            <p>Not Found</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card bg-danger text-white">
                        <div class="card-body text-center">
                            <h4>{{ count($results['errors'] ?? []) }}</h4>
                            <p>Errors</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Updated Products -->
            @if(!empty($results['updated_products']))
                <h6>Updated Products:</h6>
                <div class="table-responsive mb-3">
                    <table class="table table-sm table-striped">
                        <thead>
                            <tr>
                                <th>Product Name</th>
                                <th>Barcode</th>
                                <th>Old Price</th>
                                <th>New Price</th>
                                <th>Change</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($results['updated_products'] as $product)
                                <tr>
                                    <td>{{ $product['product_name'] }}</td>
                                    <td>{{ $product['barcode'] }}</td>
                                    <td>${{ number_format($product['old_price'], 2) }}</td>
                                    <td>${{ number_format($product['new_price'], 2) }}</td>
                                    <td>
                                        @php
                                            $change = $product['new_price'] - $product['old_price'];
                                            $changeClass = $change > 0 ? 'text-success' : 'text-danger';
                                        @endphp
                                        <span class="{{ $changeClass }}">
                                            {{ $change > 0 ? '+' : '' }}${{ number_format($change, 2) }}
                                        </span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif

            <!-- Not Found Products -->
            @if(!empty($results['not_found_products']))
                <h6>Products Not Found in Database:</h6>
                <div class="table-responsive mb-3">
                    <table class="table table-sm table-striped">
                        <thead>
                            <tr>
                                <th>Product Name</th>
                                <th>Barcode</th>
                                <th>API Price</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($results['not_found_products'] as $product)
                                <tr>
                                    <td>{{ $product['product_name'] }}</td>
                                    <td>{{ $product['barcode'] }}</td>
                                    <td>${{ number_format($product['price'], 2) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif

            <!-- Errors -->
            @if(!empty($results['errors']))
                <h6>Errors:</h6>
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach($results['errors'] as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
        </div>
    </div>
@else
    <div class="alert alert-info">
        <h6>No Synchronization Data</h6>
        <p>No synchronization has been performed yet.</p>
        <a href="{{ url()->previous() }}" class="btn btn-primary">Go Back</a>
    </div>
@endif

<!-- Command Output (for debugging) -->
@if(isset($command_output) && config('app.debug'))
    <div class="card mt-3">
        <div class="card-header">
            <h6>Command Output (Debug)</h6>
        </div>
        <div class="card-body">
            <pre class="bg-light p-3 small">{{ $command_output }}</pre>
        </div>
    </div>
@endif

<!-- Back Button -->
<div class="mt-3">
    <a href="{{ url()->previous() }}" class="btn btn-secondary">
        <i class="fas fa-arrow-left me-2"></i>Back to Dashboard
    </a>
</div>

@endsection