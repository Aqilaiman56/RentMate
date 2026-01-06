<?php

namespace App\Services;

use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;

class ToyyibPayService
{
    protected $client;
    protected $apiUrl;
    protected $secretKey;
    protected $categoryCode;

    public function __construct()
    {
        $this->client = new Client();
        $this->apiUrl = config('toyyibpay.api_url');
        $this->secretKey = config('toyyibpay.secret_key');
        $this->categoryCode = config('toyyibpay.category_code');
    }

    /**
     * Create a bill/payment
     */
    public function createBill($bookingData)
    {
        // Test mode - bypass actual API call during account verification
        if (config('toyyibpay.test_mode', false)) {
            $testBillCode = 'TEST-' . time() . '-' . rand(1000, 9999);
            Log::info('ToyyibPay Test Mode - Simulated Bill Created', [
                'bill_code' => $testBillCode,
                'booking_id' => $bookingData['booking_id']
            ]);

            return [
                'success' => true,
                'bill_code' => $testBillCode,
                'payment_url' => route('payment.test', ['bill_code' => $testBillCode])
            ];
        }

        try {
            $params = [
                'userSecretKey' => $this->secretKey,
                'categoryCode' => $this->categoryCode,
                'billName' => $bookingData['bill_name'],
                'billDescription' => $bookingData['bill_description'],
                'billPriceSetting' => 1, // 1 = Fixed price
                'billPayorInfo' => 1, // 1 = Required
                'billAmount' => $bookingData['amount'] * 100, // Amount in cents
                'billReturnUrl' => config('toyyibpay.callback_url'),
                'billCallbackUrl' => config('toyyibpay.callback_url'),
                'billExternalReferenceNo' => $bookingData['booking_id'],
                'billTo' => $bookingData['payer_name'],
                'billEmail' => $bookingData['payer_email'],
                'billPhone' => $bookingData['payer_phone'] ?? '',
                'billSplitPayment' => 0,
                'billSplitPaymentArgs' => '',
                'billPaymentChannel' => '0', // 0 = FPX, 1 = Credit Card, 2 = Both
                'billContentEmail' => 'Thank you for your payment!',
                'billChargeToCustomer' => 1, // 1 = Charge to customer
            ];

            Log::info('ToyyibPay Create Bill Request', [
                'bill_description_length' => strlen($bookingData['bill_description']),
                'bill_description' => $bookingData['bill_description'],
                'amount' => $bookingData['amount'],
                'booking_id' => $bookingData['booking_id']
            ]);

            $response = $this->client->post($this->apiUrl . 'index.php/api/createBill', [
                'form_params' => $params
            ]);

            $responseBody = $response->getBody()->getContents();
            $result = json_decode($responseBody, true);

            Log::info('ToyyibPay Create Bill Response', [
                'status_code' => $response->getStatusCode(),
                'raw_response' => $responseBody,
                'result' => $result
            ]);

            if (isset($result[0]['BillCode'])) {
                return [
                    'success' => true,
                    'bill_code' => $result[0]['BillCode'],
                    'payment_url' => $this->apiUrl . $result[0]['BillCode']
                ];
            }

            return [
                'success' => false,
                'message' => 'Failed to create bill',
                'response' => $result
            ];

        } catch (\Exception $e) {
            Log::error('ToyyibPay Create Bill Error: ' . $e->getMessage());
            return [
                'success' => false,
                'message' => $e->getMessage()
            ];
        }
    }

    /**
     * Get bill transactions
     */
    public function getBillTransactions($billCode)
    {
        try {
            $response = $this->client->post($this->apiUrl . 'index.php/api/getBillTransactions', [
                'form_params' => [
                    'billCode' => $billCode
                ]
            ]);

            $result = json_decode($response->getBody()->getContents(), true);
            return $result;

        } catch (\Exception $e) {
            Log::error('ToyyibPay Get Transactions Error: ' . $e->getMessage());
            return null;
        }
    }
}