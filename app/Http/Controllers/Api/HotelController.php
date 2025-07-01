<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class HotelController extends Controller
{

    public function sendToInvoiceSystem(Request $request)
    {

        $validated = $request->validate([
            'invoice_code' => 'required|string',
            'amount' => 'required|numeric',
            'domain' => 'required',
        ]);


        $checkdomain = Service::where('domain', $validated['domain'])->where('type', 'hotel')->first();

        if (!$checkdomain) {
            Log::info('domain');

            return response()->json([
                'success' => false,
                'message' => 'Domain không tồn tại.'
            ], 401);
        }

        if($checkdomain->status != 'active'){
            Log::info('check status');
            return response()->json([
                'success' => false,
                'message' => 'Doamin không còn hoạt dộng.'
            ], 403);
        }

        $accessToken = Service::where('domain', $validated['domain'])->where('token', $checkdomain->token)->get();

        if (!$accessToken) {
            Log::info('token');

            return response()->json([
                'success' => false,
                'message' => 'Token không hợp lệ.'
            ], 401);
        }
        Log::info(1);

        return response()->json([
            'status' => 'success',
            'message' => 'Yêu cầu thanh toán đã gửi đến POS',
            'token' => $checkdomain->token
        ]);
    }


    public function getData(Request $request)
    {
        $client = $request->get('api_client');
        $domain = $request->query('domain');
        $invoice_code = $request->query('invoice_code');

        if (!$domain || $client->domain !== $domain) {
            return response()->json(['message' => 'Bạn không có quyền truy cập domain này'], 403);
        }


        return response()->json([
            'success' => true,
            'message' => 'Token hợp lệ và có quyền truy cập domain',
            'client' => $client,
            '$domain' => $domain

        ]);
    }



}
