<?php

namespace App\Http\Controllers;

use App\Models\ConfigBank;
use App\Models\TransactionHistory;
use App\Models\User;
use App\Models\WalletTransaction;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
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
            "amount" => intval($amount),
            "description" => "VQRIO123",
            "cancelUrl" => route('payment.recharge.cancel'),
            "returnUrl" => route('payment.recharge.return', ['amount' => $amount]),
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

    public function cancelUrl()
    {
        toastr()->error('Đã hủy giao dịch.');
        return redirect()->route('payment.recharge');
    }
    public function returnUrl($amount)
    {
        $user = Auth::user();
        /**
         * @var User $user
         */
        $user->update([
            'wallet' => $amount + $user->amount
        ]);
        TransactionHistory::create([
            'code' => Str::random(10),
            'user_id' => $user->id,
            'type' => 'Nạp tiền',
            'amount' => $amount,
            'status' => 1,
            'type' => 2, /// 1 chưa, 2 thanh toán, 3 duỵet
            'description' => 'Nạp tiền vào tài khoản',
        ]);
        toastr()->success('Giao dịch thành công.');
        return redirect()->route('payment.recharge');
    }

    public function qrCode(Request $request)
    {

        $superAdmin = ConfigBank::first();
        $amount = $request->input('amount');
        //Account cá nhân
        $bank_id = $superAdmin->bank->shortName;
        $bank_account = $superAdmin->bank_account;
        //Account công ty

        $description = $request->input('description');
        $account_name = $superAdmin->name;
        // Tạo URL cho QR code
        $template = 'compact2';
        $qrCodeUrl = '';
        $qrCodeUrl = "https://img.vietqr.io/image/" . $bank_id . "-" . $bank_account . "-" . $template . ".png?amount=" . $amount . "&addInfo=" . urlencode($description) . "&accountName=" . urlencode($account_name);

        //    dd($qrCodeUrl);
        return $qrCodeUrl;
    }



    public function submitRecharge(Request $request)
    {
        // 1. Xác thực dữ liệu
        $validator = Validator::make($request->all(), [
            'amount' => 'required|numeric|min:100000|max:20000000000',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Dữ liệu không hợp lệ',
                'errors' => $validator->errors(),
            ], 422);
        }

        $amount = $request->input('amount');
        $user = Auth::user();

        try {
            // 2. Ghi lịch sử giao dịch
            // TransactionHistory::create([
            //     'user_id'     => $user->id,
            //     'amount'      => $amount,
            //     'status'      => 1, // đã nạp
            //     'type'        => 2, // nạp tiền
            //     'description' => 'Nạp tiền vào tài khoản',
            // ]);


            WalletTransaction::create([
                'user_id'     => $user->id,
                'amount'      => $amount,
                'type'        => 'deposit', // nạp tiền
                'approved_at' => now(),
                'description' => 'Nạp tiền',
            ]);

            return response()->json([
                'status' => true,
                'message' => 'Nạp tiền thành công!',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Đã xảy ra lỗi. Vui lòng thử lại.',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
