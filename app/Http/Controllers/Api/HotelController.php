<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class HotelController extends Controller
{

    public function getDataOrder(Request $request)
    {
        $client = $request->get('api_client');
        $domain = $request->query('domain');
        $invoice_code = $request->query('invoice_code');

        if (!$domain || $client->domain != $domain) {
            return response()->json(['message' => 'Bạn không có quyền truy cập domain này'], 403);
        }

        $response = Http::get("https://app.fasthotel.vn/api/get-payment/{$invoice_code}");

        Log::info($response->json());

        if ($response->successful()) {
            return response()->json([
                'status' => 'success',
                'message' => 'Thông tin đơn hàng',
                'data' => $response->json()['data']
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Mã đơn hàng không tồn tại.',
        ]);
    }


    public function checkStatus(Request $request)
    {
        $validated = $request->validate([
            'invoice_code' => 'required',
            // 'amount' => 'required',
            'domain' => 'required',
            'status' => 'required',
        ]);

        $status = filter_var($validated['status'], FILTER_VALIDATE_BOOLEAN);

        $client = Service::where('domain', $request->domain)->where('type', 'hotel')->first();

        if ($client->domain !== $validated['domain'] || $client->type !== 'hotel') {
            return response()->json([
                'success' => false,
                'message' => 'Domain không hợp lệ.',
            ], 401);
        }

        if ($client->status !== 'active') {
            return response()->json([
                'success' => false,
                'message' => 'Dịch vụ không còn hoạt động.',
            ], 403);
        }

        $orderId = $validated['invoice_code'];

        $data = [
            'invoice_code' => $orderId,
            'status' => $status,
        ];

        file_put_contents(storage_path("app/order_{$orderId}.json"), json_encode($data));

        if (!$status) {
            return response()->json([
                'invoice_code' => $orderId,
                'success' => false,
                'message' => 'Đơn hàng chưa được thanh toán.',
            ], 200);
        }

        return response()->json([
            'invoice_code' => $orderId,
            'success' => true,
            "status" => "paid",
            'message' => 'Đã ghi trạng thái thanh toán.',
        ]);
    }



    public function orderStatus($orderId)
    {
        $path = storage_path("app/order_{$orderId}.json");

        if (!file_exists($path)) {
            return response()->json([
                'invoice_code' => $orderId,
                'status' => false,
            ]);
        }

        $data = json_decode(file_get_contents($path), true);
        $status = $data['status'] ?? false;

        if ($status != false && $status != null) {
            register_shutdown_function(function () use ($path) {
                if (file_exists($path)) {
                    unlink($path);
                }
            });
        }

        return response()->json([
            'invoice_code' => $orderId,
            'status' => $status,
        ]);
    }
}
