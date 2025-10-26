<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Test Payment - RentMate</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .payment-container {
            background: white;
            border-radius: 20px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            max-width: 500px;
            width: 100%;
            padding: 40px;
        }

        .test-badge {
            background: #fbbf24;
            color: #78350f;
            padding: 8px 16px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            display: inline-block;
            margin-bottom: 20px;
        }

        h1 {
            color: #1f2937;
            font-size: 24px;
            margin-bottom: 10px;
        }

        p {
            color: #6b7280;
            margin-bottom: 30px;
            line-height: 1.6;
        }

        .payment-details {
            background: #f9fafb;
            padding: 20px;
            border-radius: 12px;
            margin-bottom: 30px;
        }

        .detail-row {
            display: flex;
            justify-content: space-between;
            padding: 10px 0;
            border-bottom: 1px solid #e5e7eb;
        }

        .detail-row:last-child {
            border-bottom: none;
        }

        .detail-label {
            color: #6b7280;
            font-size: 14px;
        }

        .detail-value {
            color: #1f2937;
            font-weight: 600;
            font-size: 14px;
        }

        .amount-display {
            background: #667eea;
            color: white;
            padding: 20px;
            border-radius: 12px;
            text-align: center;
            margin-bottom: 30px;
        }

        .amount-label {
            font-size: 14px;
            opacity: 0.9;
            margin-bottom: 5px;
        }

        .amount-value {
            font-size: 36px;
            font-weight: 700;
        }

        .btn-group {
            display: flex;
            gap: 12px;
        }

        .btn {
            flex: 1;
            padding: 14px;
            border-radius: 10px;
            border: none;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
        }

        .btn-success {
            background: #10b981;
            color: white;
        }

        .btn-success:hover {
            background: #059669;
            transform: translateY(-2px);
        }

        .btn-fail {
            background: #ef4444;
            color: white;
        }

        .btn-fail:hover {
            background: #dc2626;
            transform: translateY(-2px);
        }

        .info-box {
            background: #dbeafe;
            border-left: 4px solid #3b82f6;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
        }

        .info-box p {
            color: #1e40af;
            font-size: 13px;
            margin: 0;
        }
    </style>
</head>
<body>
    <div class="payment-container">
        <span class="test-badge"><i class="fas fa-flask"></i> TEST MODE</span>

        <h1>Simulated Payment Gateway</h1>
        <p>This is a test payment page. Your ToyyibPay account is under verification. Click a button below to simulate payment success or failure.</p>

        <div class="info-box">
            <p><strong>Note:</strong> Once your ToyyibPay account is verified, this will redirect to the actual ToyyibPay payment page.</p>
        </div>

        <div class="payment-details">
            <div class="detail-row">
                <span class="detail-label">Bill Code</span>
                <span class="detail-value">{{ $billCode }}</span>
            </div>
            <div class="detail-row">
                <span class="detail-label">Item</span>
                <span class="detail-value">{{ $booking->item->ItemName }}</span>
            </div>
            <div class="detail-row">
                <span class="detail-label">Booking ID</span>
                <span class="detail-value">#{{ $booking->BookingID }}</span>
            </div>
            <div class="detail-row">
                <span class="detail-label">Payer</span>
                <span class="detail-value">{{ $booking->user->UserName }}</span>
            </div>
        </div>

        <div class="amount-display">
            <div class="amount-label">Total Amount</div>
            <div class="amount-value">RM {{ number_format($payment->Amount, 2) }}</div>
        </div>

        <div class="btn-group">
            <button class="btn btn-success" onclick="simulatePayment('success')">
                <i class="fas fa-check"></i> Pay Now (Success)
            </button>
            <button class="btn btn-fail" onclick="simulatePayment('fail')">
                <i class="fas fa-times"></i> Cancel (Failed)
            </button>
        </div>
    </div>

    <script>
        function simulatePayment(status) {
            const statusId = status === 'success' ? 1 : 3;
            const transactionId = 'TEST-' + Date.now();

            // Simulate callback to your application
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = '{{ route("payment.callback") }}';

            const fields = {
                '_token': '{{ csrf_token() }}',
                'billcode': '{{ $billCode }}',
                'status_id': statusId,
                'transaction_id': transactionId,
                'order_id': '{{ $booking->BookingID }}'
            };

            for (const key in fields) {
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = key;
                input.value = fields[key];
                form.appendChild(input);
            }

            document.body.appendChild(form);
            form.submit();
        }
    </script>
</body>
</html>
