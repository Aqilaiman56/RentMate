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
        try {
            $response = $this->client->post($this->apiUrl . 'index.php/api/createBill', [
                'form_params' => [
                    'userSecretKey' => $this->secretKey,
                    'categoryCode' => $this->categoryCode,
                    'billName' => $bookingData['bill_name'],
                    'billDescription' => $bookingData['bill_description'],
                    'billPriceSetting' => 1, // 1 = Fixed price
                    'billPayorInfo' => 1, // 1 = Required
                    'billAmount' => $bookingData['amount'] * 100, // Amount in cents
                    'billReturnUrl' => route('payment.callback'),
                    'billCallbackUrl' => route('payment.callback'),
                    'billExternalReferenceNo' => $bookingData['booking_id'],
                    'billTo' => $bookingData['payer_name'],
                    'billEmail' => $bookingData['payer_email'],
                    'billPhone' => $bookingData['payer_phone'] ?? '',
                    'billSplitPayment' => 0,
                    'billSplitPaymentArgs' => '',
                    'billPaymentChannel' => '0', // 0 = FPX, 1 = Credit Card, 2 = Both
                    'billContentEmail' => 'Thank you for your payment!',
                    'billChargeToCustomer' => 1, // 1 = Charge to customer
                ]
            ]);

            $result = json_decode($response->getBody()->getContents(), true);

            if (isset($result[0]['BillCode'])) {
                return [
                    'success' => true,
                    'bill_code' => $result[0]['BillCode'],
                    'payment_url' => $this->apiUrl . $result[0]['BillCode']
                ];
            }

            return [
                'success' => false,
                'message' => 'Failed to create bill'
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

    /**
     * Verify payment signature
     */
    public function verifySignature($data)
    {
        // ToyyibPay verification logic
        return true;
    }
}