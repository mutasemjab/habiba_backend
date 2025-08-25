<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Preparation Invoice #{{ $order->id }}</title>
    <style>
        /* Thermal Paper Optimized Styles */
        @media print {
            @page {
                margin: 0;
                size: 80mm auto; /* Standard thermal paper width */
            }
            body {
                margin: 0;
                padding: 5mm;
            }
        }

        body {
            font-family: 'Courier New', monospace;
            font-size: 12px;
            line-height: 1.2;
            max-width: 70mm;
            margin: 0 auto;
            padding: 5mm;
            background: white;
            color: black;
        }

        .header {
            text-align: center;
            margin-bottom: 10px;
            border-bottom: 1px dashed #000;
            padding-bottom: 8px;
        }

        .company-name {
            font-size: 16px;
            font-weight: bold;
            margin-bottom: 2px;
        }

        .invoice-title {
            font-size: 14px;
            font-weight: bold;
            margin: 5px 0;
        }

        .order-info {
            margin: 8px 0;
            font-size: 11px;
        }

        .customer-info {
            margin: 8px 0;
            border-top: 1px dashed #000;
            border-bottom: 1px dashed #000;
            padding: 5px 0;
            font-weight: bold;
            font-size: 15px;
        }

        .items-table {
            width: 100%;
            margin: 8px 0;
        }

        .items-header {
            border-bottom: 1px solid #000;
            padding-bottom: 2px;
            margin-bottom: 5px;
            font-weight: bold;
        }

        .item-row {
            margin-bottom: 3px;
            padding-bottom: 2px;
        }

        .item-name {
            font-weight: bold;
             font-size: 18px;
        }

        .item-details {
            font-size: 15px;
            font-weight: bold;
            color: #333;
        }

        .totals {
            border-top: 1px dashed #000;
            padding-top: 5px;
            margin-top: 10px;
        }

        .total-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 2px;
        }

        .final-total {
            border-top: 1px solid #000;
            padding-top: 3px;
            margin-top: 5px;
            font-weight: bold;
            font-size: 18px;
        }

        .footer {
            text-align: center;
            margin-top: 15px;
            border-top: 1px dashed #000;
            padding-top: 8px;
            font-size: 10px;
        }

        .print-button {
            position: fixed;
            top: 10px;
            right: 10px;
            padding: 10px 20px;
            background: #007bff;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 14px;
        }

        @media print {
            .print-button {
                display: none;
            }
        }

        .address-info {
            font-size: 10px;
            margin: 3px 0;
        }

        .status-badge {
            display: inline-block;
            padding: 2px 6px;
            border: 1px solid #000;
            font-size: 10px;
            margin-left: 5px;
        }
    </style>
</head>
<body>
    <button class="print-button" onclick="window.print()">🖨️ Print</button>

    <div class="header">
        <div class="company-name">Habiba Store</div>
        <div class="invoice-title">PREPARATION INVOICE</div>
    </div>

    <div class="order-info">
        <div><strong>Order #:</strong> {{ $order->id }}</div>
        <div><strong>Date:</strong> {{ $order->created_at->format('d/m/Y H:i') }}</div>
        <div><strong>Status:</strong> {{ ucfirst($order->status) }} 
            <span class="status-badge">{{ strtoupper($order->status) }}</span>
        </div>
        @if($order->branch_id)
        <div><strong>Branch:</strong> {{ $order->branch->branch_title ?? 'N/A' }}</div>
        @endif
        @if($order->driver_id)
        <div><strong>Driver:</strong> {{ $order->driver->name ?? 'N/A' }}</div>
        @endif
    </div>

    <div class="customer-info">
        <div><strong>Customer:</strong> {{ $order->client->name }}</div>
        @if($order->client->mobile)
        <div><strong>Phone:</strong> {{ $order->client->mobile }}</div>
        @endif
        
        @if($order->address_mark)
        <div class="customer-info">
            <strong>Delivery Address:</strong><br>
            {{ $order->address_mark }}
        </div>
        @endif
    </div>

    <div class="items-table">
        <div class="items-header">
            ITEMS TO PREPARE
        </div>
        
        @foreach($order->orderItems as $item)
        <div class="item-row">
            <div class="item-name">
                {{ $item->product->ar_product_name }}
            </div>
            <div class="item-details">
                Qty: {{ $item->product_qty }} × JD {{ number_format($item->price_at_time, 2) }}
                = JD {{ number_format($item->product_qty * $item->price_at_time, 2) }}
            </div>
            @if($item->product->description)
            <div class="item-details">
                <em>{{ Str::limit($item->product->description, 50) }}</em>
            </div>
            @endif
        </div>
        @endforeach
    </div>

    <div class="totals">
        @if($order->original_cost)
        <div class="total-row">
            <span>Original Cost:</span>
            <span>JD {{ number_format($order->original_cost, 2) }}</span>
        </div>
        @endif

        @if($order->total_discount)
        <div class="total-row">
            <span>Total Discount:</span>
            <span>-JD {{ number_format($order->total_discount, 2) }}</span>
        </div>
        @endif

        @if($order->coupon_discount_value)
        <div class="total-row">
            <span>Coupon Discount:</span>
            <span>-JD {{ number_format($order->coupon_discount_value, 2) }}</span>
        </div>
        @endif

        @if($order->delivery_cost)
        <div class="total-row">
            <span>Delivery Cost:</span>
            <span>JD {{ number_format($order->delivery_cost, 2) }}</span>
        </div>
        @endif

        <div class="total-row final-total">
            <span>TOTAL:</span>
            <span>JD {{ number_format($order->total_price + $order->delivery_cost, 2) }}</span>
        </div>
    </div>

    <div class="footer">
        <div>Preparation Time: {{ now()->format('H:i') }}</div>
        <div style="margin-top: 10px;">================================</div>
        <div>Thank you!</div>
    </div>
</body>
</html>