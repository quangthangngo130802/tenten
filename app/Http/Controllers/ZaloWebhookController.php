<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ZaloWebhookController extends Controller
{
    private $accessToken = 'YOUR_ZALO_OA_ACCESS_TOKEN';

    public function handle(Request $request)
    {
        $data = $request->all();
        Log::info('Webhook Zalo:', $data);

    }

    private function getZaloUserProfile($userId)
    {
        $response = Http::get('https://openapi.zalo.me/v2.0/oa/getprofile', [
            'access_token' => $this->accessToken,
            'user_id' => $userId
        ]);

        if ($response->ok()) {
            return $response->json()['data'] ?? null;
        }

        Log::error('Zalo profile error', ['response' => $response->body()]);
        return null;
    }
}
