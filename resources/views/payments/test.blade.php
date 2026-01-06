<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>toyyibPay - Payment Gateway</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Arial', sans-serif;
            background: #f5f5f5;
            min-height: 100vh;
        }

        /* Header */
        .header {
            background: #ffffff;
            border-bottom: 3px solid #00a651;
            padding: 15px 0;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }

        .header-content {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .logo {
            font-size: 28px;
            font-weight: bold;
            color: #00a651;
        }

        .logo-text {
            color: #333;
        }

        .test-indicator {
            background: #ff9800;
            color: white;
            padding: 4px 12px;
            border-radius: 12px;
            font-size: 11px;
            font-weight: bold;
            margin-left: 10px;
        }

        /* Main Container */
        .container {
            max-width: 900px;
            margin: 30px auto;
            padding: 0 20px;
        }

        /* Payment Info Card */
        .payment-card {
            background: white;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            overflow: hidden;
            margin-bottom: 20px;
        }

        .card-header {
            background: linear-gradient(135deg, #00a651 0%, #008d43 100%);
            color: white;
            padding: 20px 25px;
        }

        .card-header h1 {
            font-size: 22px;
            margin-bottom: 5px;
        }

        .card-header p {
            font-size: 14px;
            opacity: 0.9;
        }

        .card-body {
            padding: 30px 25px;
        }

        /* Bill Details */
        .bill-details {
            background: #f9f9f9;
            border: 1px solid #e0e0e0;
            border-radius: 6px;
            padding: 20px;
            margin-bottom: 25px;
        }

        .bill-details h3 {
            color: #333;
            font-size: 16px;
            margin-bottom: 15px;
            padding-bottom: 10px;
            border-bottom: 2px solid #00a651;
        }

        .detail-row {
            display: flex;
            justify-content: space-between;
            padding: 12px 0;
            border-bottom: 1px solid #e8e8e8;
        }

        .detail-row:last-child {
            border-bottom: none;
        }

        .detail-label {
            color: #666;
            font-size: 14px;
            font-weight: 500;
        }

        .detail-value {
            color: #333;
            font-weight: 600;
            font-size: 14px;
            text-align: right;
        }

        /* Amount Section */
        .amount-section {
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            border: 2px solid #00a651;
            border-radius: 8px;
            padding: 25px;
            text-align: center;
            margin-bottom: 30px;
        }

        .amount-label {
            color: #666;
            font-size: 14px;
            font-weight: 500;
            margin-bottom: 8px;
        }

        .amount-value {
            color: #00a651;
            font-size: 42px;
            font-weight: bold;
            letter-spacing: -1px;
        }

        /* Payment Methods */
        .payment-methods {
            margin-bottom: 25px;
        }

        .payment-methods h3 {
            color: #333;
            font-size: 16px;
            margin-bottom: 15px;
        }

        .method-option {
            background: white;
            border: 2px solid #e0e0e0;
            border-radius: 8px;
            padding: 15px 20px;
            margin-bottom: 12px;
            cursor: pointer;
            transition: all 0.3s;
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .method-option:hover {
            border-color: #00a651;
            background: #f8fff8;
        }

        .method-option.selected {
            border-color: #00a651;
            background: #f0f9f4;
        }

        .method-icon {
            width: 50px;
            height: 35px;
            background: #f5f5f5;
            border-radius: 4px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 12px;
            font-weight: bold;
            color: #666;
        }

        .method-info {
            flex: 1;
        }

        .method-name {
            font-size: 15px;
            font-weight: 600;
            color: #333;
            margin-bottom: 3px;
        }

        .method-desc {
            font-size: 12px;
            color: #999;
        }

        /* Action Buttons */
        .action-buttons {
            display: flex;
            gap: 15px;
            margin-top: 30px;
        }

        .btn {
            flex: 1;
            padding: 16px;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }

        .btn-primary {
            background: #00a651;
            color: white;
        }

        .btn-primary:hover {
            background: #008d43;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 166, 81, 0.3);
        }

        .btn-secondary {
            background: #dc3545;
            color: white;
        }

        .btn-secondary:hover {
            background: #c82333;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(220, 53, 69, 0.3);
        }

        /* Info Box */
        .info-box {
            background: #fff3cd;
            border: 1px solid #ffc107;
            border-left: 4px solid #ffc107;
            padding: 15px;
            border-radius: 6px;
            margin-bottom: 20px;
        }

        .info-box p {
            color: #856404;
            font-size: 13px;
            margin: 0;
            line-height: 1.5;
        }

        /* Footer */
        .footer {
            text-align: center;
            padding: 20px;
            color: #999;
            font-size: 12px;
        }

        .secure-badge {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            background: #f0f0f0;
            padding: 8px 15px;
            border-radius: 20px;
            margin-top: 15px;
            font-size: 12px;
            color: #666;
        }

        .lock-icon {
            width: 16px;
            height: 16px;
            background: #00a651;
            border-radius: 3px;
            display: inline-block;
        }
    </style>
</head>
<body>
    <!-- Header -->
    <div class="header">
        <div class="header-content">
            <div class="logo">toyyib<span class="logo-text">Pay</span></div>
            <span class="test-indicator">🧪 SANDBOX MODE</span>
        </div>
    </div>

    <!-- Main Container -->
    <div class="container">
        <!-- Info Box -->
        <div class="info-box">
            <p><strong>⚠️ Test Environment:</strong> This is a simulated payment page for local testing. In production, users will be redirected to the actual toyyibPay payment gateway.</p>
        </div>

        <!-- Payment Card -->
        <div class="payment-card">
            <div class="card-header">
                <h1>Payment Details</h1>
                <p>Please review your payment information below</p>
            </div>

            <div class="card-body">
                <!-- Bill Details -->
                <div class="bill-details">
                    <h3>📋 Bill Information</h3>
                    <div class="detail-row">
                        <span class="detail-label">Bill Code</span>
                        <span class="detail-value">{{ $billCode }}</span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label">Bill Name</span>
                        <span class="detail-value">Security Deposit - Booking #{{ $booking->BookingID }}</span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label">Description</span>
                        <span class="detail-value">{{ $booking->item->ItemName }}</span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label">Order ID</span>
                        <span class="detail-value">#{{ $booking->BookingID }}</span>
                    </div>
                </div>

                <!-- Payer Information -->
                <div class="bill-details">
                    <h3>👤 Payer Information</h3>
                    <div class="detail-row">
                        <span class="detail-label">Name</span>
                        <span class="detail-value">{{ $booking->user->UserName }}</span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label">Email</span>
                        <span class="detail-value">{{ $booking->user->Email }}</span>
                    </div>
                    @if($booking->user->PhoneNumber)
                    <div class="detail-row">
                        <span class="detail-label">Phone</span>
                        <span class="detail-value">{{ $booking->user->PhoneNumber }}</span>
                    </div>
                    @endif
                </div>

                <!-- Amount -->
                <div class="amount-section">
                    <div class="amount-label">Total Payment Amount</div>
                    <div class="amount-value">RM {{ number_format($payment->Amount, 2) }}</div>
                </div>

                <!-- Payment Methods -->
                <div class="payment-methods">
                    <h3>💳 Select Payment Method</h3>
                    <div class="method-option selected" id="fpx-method">
                        <div class="method-icon">FPX</div>
                        <div class="method-info">
                            <div class="method-name">FPX (Online Banking)</div>
                            <div class="method-desc">Pay directly from your bank account</div>
                        </div>
                    </div>
                    <div class="method-option" id="card-method">
                        <div class="method-icon">💳</div>
                        <div class="method-info">
                            <div class="method-name">Credit/Debit Card</div>
                            <div class="method-desc">Visa, Mastercard accepted</div>
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="action-buttons">
                    <button class="btn btn-primary" onclick="simulatePayment('success')">
                        ✓ Proceed to Pay
                    </button>
                    <button class="btn btn-secondary" onclick="simulatePayment('fail')">
                        ✕ Cancel Payment
                    </button>
                </div>

                <!-- Secure Badge -->
                <div style="text-align: center;">
                    <div class="secure-badge">
                        <span class="lock-icon"></span>
                        Secured by toyyibPay
                    </div>
                </div>
            </div>
        </div>

        <!-- Footer -->
        <div class="footer">
            <p>© 2026 toyyibPay. All rights reserved.</p>
            <p>This is a test page for development purposes only</p>
        </div>
    </div>

    <script>
        // Payment method selection
        document.querySelectorAll('.method-option').forEach(option => {
            option.addEventListener('click', function() {
                document.querySelectorAll('.method-option').forEach(o => o.classList.remove('selected'));
                this.classList.add('selected');
            });
        });

        function simulatePayment(status) {
            const statusId = status === 'success' ? 1 : 3;
            const transactionId = 'TEST-' + Date.now();

            // Show processing message
            if (status === 'success') {
                if (!confirm('Simulate successful payment?\n\nThis will mark the payment as completed.')) {
                    return;
                }
            } else {
                if (!confirm('Cancel this payment?\n\nThis will mark the payment as failed.')) {
                    return;
                }
            }

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
