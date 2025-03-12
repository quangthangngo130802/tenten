<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Service;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ServiceController extends Controller
{
    //

    public function store(Request $request)
    {
        Log::info($request->all());
        try {

            $service = Service::create([
                'email' => $request->email,
                'type' => 'hotel',
                'domain' => $request->domain,
                'active_at' => $request->active_at,
                'number' => $request->number,
                'status' =>  $request->status,
                'price' => 0
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Thêm dịch vụ thành công',
                'data' => $service
            ], 201);
        } catch (QueryException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Đã xảy ra lỗi khi thêm dịch vụ. Vui lòng thử lại!',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
