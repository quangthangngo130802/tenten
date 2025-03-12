<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class CustomerController extends Controller
{
    public function store(Request $request)
    {
        try {
            // Kiểm tra xem email hoặc username đã tồn tại chưa
            if (User::where('email', $request->email)->exists()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Email đã tồn tại'
                ], 400);
            }

            if (User::where('username', $request->username)->exists()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Username đã tồn tại'
                ], 400);
            }

            $customer = User::create([
                'full_name' => $request->name,
                'username' => $request->username,
                'email' => $request->email,
                'phone_number' => $request->phone,
                'password' => $request->password,
                'province' => $request->province,
                'status' => 'active',
                'is_hotel_account' => 1
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Thêm khách hàng thành công',
                'data' => $customer
            ], 201);
        } catch (QueryException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Đã xảy ra lỗi khi thêm khách hàng. Vui lòng thử lại!',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function list()
    {
        $list = User::get();
        return response()->json([
            'message' => 'Danh sachs khách hàng',
            'data' => $list
        ], 201);
    }
}
