<?php

namespace App\Http\Controllers;

use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PaymentController extends Controller
{
    public function recharge()
    {
        return view('backend.payment.recharge');
    }

    public function createPayment(Request $request)
    {
        // Kiểm tra số tiền
        $amount = round($request->price);

        // Kiểm tra số tiền hợp lệ
        // if ($amount < 100000 || $amount > 10000000000) {
        //     return response()->json([
        //         'error' => 'Số tiền không được nhỏ hơn 100.000 đ và không được lớn hơn 10.000.000.000 đ.',
        //     ], 400);
        // }

        // Kiểm tra số tiền là số dương
        if ($amount <= 0) {
            return response()->json([
                'error' => 'Số tiền phải là số dương.',
            ], 400);
        }

        // Còn lại xử lý yêu cầu tạo thanh toán như hiện tại
        $clientId = env('PAYOS_CLIENT_ID');
        $apiKey = env('PAYOS_API_KEY');
        $checksumKey = env('PAYOS_CHECKSUM_KEY');
        $orderCode = substr(str_shuffle("0123456789"), 0, 11);

        $data = [
            "orderCode" => intval($orderCode),
            "amount" => intval( $amount),
            "description" => "VQRIO123",
            "cancelUrl" => route('payment.recharge.cancel'),
            "returnUrl" => route('payment.recharge.return'),
            "expiredAt" => time() + 3600,
        ];

        $data['signature'] = $this->createSignatureOfPaymentRequest($checksumKey, $data);

        $client = new Client();

        try {
            $response = $client->post('https://api-merchant.payos.vn/v2/payment-requests', [
                'headers' => [
                    'x-client-id' => $clientId,
                    'x-api-key' => $apiKey,
                    'Content-Type' => 'application/json',
                ],
                'json' => $data,
            ]);

            $responseBody = json_decode($response->getBody(), true);

            if (isset($responseBody['data']['checkoutUrl'])) {
                $paymentLink = $responseBody['data']['checkoutUrl'];
                return redirect()->to($paymentLink);
            }

            return response()->json([
                'success' => $responseBody,
                'status_code' => $response->getStatusCode(),
            ], $response->getStatusCode());
        } catch (\Exception $e) {
            return response()->json([
                'error' => $e->getMessage(),
            ], 500);
        }
    }


    public function createSignatureOfPaymentRequest($checksumKey, $obj)
    {
        $dataStr = "amount={$obj["amount"]}&cancelUrl={$obj["cancelUrl"]}&description={$obj["description"]}&orderCode={$obj["orderCode"]}&returnUrl={$obj["returnUrl"]}";
        $signature = hash_hmac("sha256", $dataStr, $checksumKey);

        return $signature;
    }

    public function cancelUrl(){
        toastr()->error('Đã hủy giao dịch.');
        return redirect()->route('payment.recharge');
    }
    public function returnUrl(){
        toastr()->success('Giao dịch thành công.');
        return redirect()->route('payment.recharge');
    }
}
