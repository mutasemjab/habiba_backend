@extends('layouts.main')
@section('navbar')
    @include('layouts.navbar')
@endsection
@section('sidebar')
    @include('layouts.sidebar')
@endsection
@section('title')
    {{ __('messages.products') }}
@endsection
@section('content')
    <div class="row">
        <div class="col-lg-12 mb-4">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex justify-content-between align-items-center">
                    <h6 class="m-0 font-weight-bold text-primary">{{ __('messages.products_index') }}</h6>
                    <div>
                        <a href="{{ route('products.create') }}" class="btn btn-primary">{{ __('messages.new_product') }}</a>
                        <a href="{{ route('products.export') }}" class="btn btn-secondary">
                            {{ __('messages.Export all products') }}
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <form action="{{ route('products.index') }}" method="GET" class="mb-4">
                        <div class="input-group mb-2">
                            <input type="text" class="form-control" name="search" 
                                   placeholder="{{ __('messages.search_by_name_or_barcode_or_price') }}" 
                                   value="{{ $search ?? '' }}">
                            <select name="has_barcode" class="form-control ml-2">
                                <option value="">{{ __('messages.all_products') }}</option>
                                <option value="1" {{ request('has_barcode') == '1' ? 'selected' : '' }}>
                                    {{ __('messages.with_barcode') }}
                                </option>
                                <option value="0" {{ request('has_barcode') == '0' ? 'selected' : '' }}>
                                    {{ __('messages.without_barcode') }}
                                </option>
                            </select>
                            <div class="input-group-append">
                                <button class="btn btn-primary" type="submit">
                                    <i class="fas fa-search fa-sm"></i> {{ __('messages.search') }}
                                </button>
                                @if(isset($search) || isset($hasBarcode))
                                    <a href="{{ route('products.index') }}" class="btn btn-secondary">
                                        <i class="fas fa-times fa-sm"></i> {{ __('messages.clear') }}
                                    </a>
                                @endif
                            </div>
                        </div>
                    </form>

                    <!-- Add products count display -->
                    <div class="mb-3">
                        <small class="text-muted">
                            Showing {{ $products->firstItem() ?? 0 }} to {{ $products->lastItem() ?? 0 }} 
                            of {{ $products->total() }} products
                        </small>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-bordered" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>{{ __('messages.image') }}</th>
                                    <th>{{ __('messages.product_name') }}</th>
                                    <th>{{ __('messages.ar_product_name') }}</th>
                                    <th>{{ __('messages.category_name') }}</th>
                                    <th>{{ __('messages.sub_category_name') }}</th>
                                    <th>{{ __('messages.brand_name') }}</th>
                                    <th>{{ __('messages.price') }}</th>
                                    <th>{{ __('messages.status') }}</th>
                                    <th>{{ __('messages.barcode') }}</th>
                                    <th>{{ __('messages.actions') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($products as $product)
                                    <tr>
                                        <td>{{ ($products->currentPage() - 1) * $products->perPage() + $loop->iteration }}</td>
                                        <td class="text-center">
                                            @if($product->image)
                                                <img src="{{ asset("products/images/$product->image") }}" 
                                                     width="50" height="50" class="img-thumbnail" 
                                                     loading="lazy" alt="Product Image">
                                            @else
                                                <div class="bg-light d-flex align-items-center justify-content-center" 
                                                     style="width: 50px; height: 50px;">
                                                    <i class="fas fa-image text-muted"></i>
                                                </div>
                                            @endif
                                        </td>
                                      <!-- In your table -->
<td>{{ $product->english_name }}</td>
<td>{{ $product->arabic_name }}</td>
                                        <td>{{ $product->category->category_name ?? 'N/A' }}</td>
                                        <td>{{ $product->sub_category->sub_category_name ?? 'N/A' }}</td>
                                        <td>{{ $product->brand->brand_name ?? 'N/A' }}</td>
                                        <td>{{ number_format($product->price, 2) }}</td>
                                        <td>
                                            @if ($product->product_status == '1')
                                                <span class="badge bg-success text-white">
                                                    {{ __('messages.active') }}
                                                </span>
                                            @else
                                                <span class="badge bg-danger text-white">
                                                    {{ __('messages.disabled') }}
                                                </span>
                                            @endif
                                        </td>
                                        <td>
                                            <input type="text" class="barcode-input form-control form-control-sm" 
                                                   data-product-id="{{ $product->id }}" 
                                                   value="{{ $product->barcode }}" 
                                                   placeholder="Enter Barcode">
                                        </td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                @if ($product->product_status == '1')
                                                    <form action="{{ route('products.toggle', $product->id) }}" method="POST" class="d-inline">
                                                        @csrf
                                                        <button type="submit" class="btn btn-danger btn-sm"
                                                            onclick="return confirm('{{ __('messages.confirm_disable_product') }}')">
                                                            <i class="fa-solid fa-circle-xmark"></i>
                                                        </button>
                                                    </form>
                                                @else
                                                    <form action="{{ route('products.toggle', $product->id) }}" method="POST" class="d-inline">
                                                        @csrf
                                                        <button type="submit" class="btn btn-success btn-sm"
                                                            onclick="return confirm('{{ __('messages.confirm_activate_product') }}')">
                                                            <i class="fa-solid fa-check"></i>
                                                        </button>
                                                    </form>
                                                @endif
                                                <a href="{{ route('products.edit', $product->id) }}" class="btn btn-primary btn-sm">
                                                    <i class="fa-solid fa-pen-to-square"></i>
                                                </a>
                                                <form action="{{ route('products.destroy', $product->id) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    <button type="submit" class="btn btn-danger btn-sm"
                                                        onclick="return confirm('{{ __('messages.confirm_delete_product') }}')">
                                                        <i class="fa-solid fa-trash"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="11" class="text-center">No products found</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Add pagination links -->
                    <div class="d-flex justify-content-between align-items-center mt-3">
                        <div>
                            <small class="text-muted">
                                Showing {{ $products->firstItem() ?? 0 }} to {{ $products->lastItem() ?? 0 }} 
                                of {{ $products->total() }} results
                            </small>
                        </div>
                        <div>
                            {{ $products->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        $(document).ready(function() {
            // Remove DataTable initialization since we're using Laravel pagination
            
            // Debounce function to prevent excessive AJAX calls
            function debounce(func, wait) {
                let timeout;
                return function executedFunction(...args) {
                    const later = () => {
                        clearTimeout(timeout);
                        func(...args);
                    };
                    clearTimeout(timeout);
                    timeout = setTimeout(later, wait);
                };
            }

            // Handle barcode updates with debouncing
            $(document).on('keypress', '.barcode-input', function(event) {
                if (event.which == 13) { // Enter key
                    event.preventDefault();
                    updateBarcode($(this));
                }
            });

            // Optional: Auto-save on blur with debouncing
            $(document).on('blur', '.barcode-input', debounce(function() {
                if ($(this).data('changed')) {
                    updateBarcode($(this));
                }
            }, 500));

            $(document).on('input', '.barcode-input', function() {
                $(this).data('changed', true);
            });

            function updateBarcode(inputField) {
                let barcode = inputField.val();
                let productId = inputField.data('product-id');
                
                // Show loading state
                inputField.prop('disabled', true).css('border', '2px solid #ffc107');

                $.ajax({
                    url: '{{ route("products.updateBarcode") }}',
                    type: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        id: productId,
                        barcode: barcode
                    },
                    success: function(response) {
                        if (response.success) {
                            inputField.css('border', '2px solid #28a745'); // Green for success
                            inputField.data('changed', false);
                            setTimeout(() => {
                                inputField.css('border', '');
                            }, 2000);
                        } else {
                            inputField.css('border', '2px solid #dc3545'); // Red for error
                            alert('Failed to update barcode: ' + (response.message || 'Unknown error'));
                        }
                    },
                    error: function(xhr) {
                        inputField.css('border', '2px solid #dc3545');
                        let errorMessage = 'Error updating barcode.';
                        if (xhr.responseJSON && xhr.responseJSON.message) {
                            errorMessage = xhr.responseJSON.message;
                        }
                        alert(errorMessage);
                    },
                    complete: function() {
                        inputField.prop('disabled', false);
                    }
                });
            }
        });
    </script>
@endsection