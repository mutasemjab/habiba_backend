@extends('layouts.main')

@section('navbar')
    @include('layouts.navbar')
@endsection

@section('sidebar')
    @include('layouts.sidebar')
@endsection

@section('title')
    {{ __('messages.withdraw_cash_from_driver_wallet') }}
@endsection

@section('content')
    <div class="row">
        <div class="col-lg-12 mb-4">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">{{ __('messages.withdraw_cash_from_driver_wallet') }}</h6>
                </div>

                <div class="card-body">
                    <form action="{{ route('empty_petty_cash') }}" method="POST" id="withdraw-form">
                        @csrf

                        <div class="form-group">
                            <label for="driver_id">{{ __('messages.driver') }}</label>
                            <select name="driver_id" id="driver_id" class="form-control" required>
                                <option value="">{{ __('messages.select_driver') }}</option>
                                @foreach ($drivers as $driver)
                                    <option value="{{ $driver->id }}" data-wallet="{{ $driver->wallet }}">
                                        {{ $driver->name }} - {{ __('messages.wallet') }}: {{ $driver->wallet }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="profit_percentage">{{ __('messages.profit_percentage') }}</label>
                            <input type="number" class="form-control" id="profit_percentage" name="profit_percentage"
                                min="0" max="100" step="1" required>
                        </div>

                        <div class="form-group">
                            <label for="final_amount">{{ __('messages.final_amount_to_withdraw') }}</label>
                            <input type="text" class="form-control" id="final_amount" name="final_amount" readonly>
                        </div>

                        <div class="form-group">
                            <label for="driver_profit">{{ __('messages.driver_profit') }}</label>
                            <input type="text" class="form-control" id="driver_profit" name="driver_profit" readonly>
                        </div>
                        <hr>
                        <button type="submit" class="btn btn-primary" id="withdraw-btn" disabled>
                            {{ __('messages.withdraw') }}
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
    <script>
        $(document).ready(function() {
            // Update final amount and driver profit when driver is selected or profit percentage is changed
            function updateFinalAmount() {
                var selectedDriver = $('#driver_id').find(':selected');
                var walletBalance = parseFloat(selectedDriver.data('wallet'));
                var profitPercentage = parseFloat($('#profit_percentage').val());

                // Calculate final amount if wallet balance and profit percentage are available
                if (walletBalance && profitPercentage >= 0 && profitPercentage <= 100) {
                    var finalAmount = walletBalance - (walletBalance * (profitPercentage / 100));
                    var driverProfit = walletBalance * (profitPercentage / 100);

                    $('#final_amount').val(finalAmount.toFixed(2));
                    $('#driver_profit').val(driverProfit.toFixed(2));

                    // Enable the withdraw button if final amount is greater than 0
                    if (finalAmount > 0) {
                        $('#withdraw-btn').prop('disabled', false);
                    } else {
                        $('#withdraw-btn').prop('disabled', true);
                    }

                    // Enable the show driver profit button if driver profit is greater than 0
                    if (driverProfit > 0) {
                        $('#driver-profit-btn').prop('disabled', false);
                    } else {
                        $('#driver-profit-btn').prop('disabled', true);
                    }
                } else {
                    $('#final_amount').val('');
                    $('#driver_profit').val('');
                    $('#withdraw-btn').prop('disabled', true);
                    $('#driver-profit-btn').prop('disabled', true);
                }
            }

            // Trigger the function when driver is selected
            $('#driver_id').change(function() {
                updateFinalAmount();
            });

            // Trigger the function when profit percentage is changed
            $('#profit_percentage').on('input', function() {
                updateFinalAmount();
            });

            // Prevent form submission if wallet balance is 0
            $('#withdraw-form').submit(function(event) {
                var selectedDriver = $('#driver_id').find(':selected');
                var walletBalance = parseFloat(selectedDriver.data('wallet'));
                if (walletBalance === 0) {
                    event.preventDefault(); // Prevent form submission
                    alert("{{ __('messages.wallet_balance_is_zero') }}");
                }
            });
        });
    </script>
@endsection
