<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use App\Models\UserZalo;
use App\Models\ZaloOa;
use App\Models\ZnsMessage;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ZaloController extends Controller
{
    //
    public function addZaloMessage(Request $request)   // thm tin nhắn
    {
        Log::info('Receiving data from Admin ', $request->all());
        try {
            $zns = ZnsMessage::create($request->all());
            if($request->status == 1){
                $user = UserZalo::find($request->user_id);
                if ($user) {
                    $user->swallet -= $zns->template->price;
                    $user->save();
                } else {
                    Log::error('User not found for ID: ' . $request->user_id);
                    return response()->json(['error' => 'User not found'], 404);
                }
            }
            Log::info('Message added to SuperAdmin Successfully');
            return response()->json(['success' => 'Thêm Zalo Super Admin thành công']);
        } catch (Exception $e) {
            Log::error('Failed to add zalo to Super Admin: ' . $e->getMessage());
            return response()->json(['error' => 'Thêm Zalo Super Admin thất bại']);
        }
    }


    public function addTransaction(Request $request) /// lịch sử giao dịch
    {
        Log::info($request->all());
        DB::beginTransaction();
        try {
            $transaction = Transaction::create([
                'amount' => $request->input('amount'),
                'status' => $request->input('status'),
                'user_id' => $request->input('user_id'),
                'notification' => $request->input('notification'),
                'description' => $request->input('description'),
            ]);

            DB::commit();
            return response()->json(['message' => 'Transaction created successfully']);
        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Failed to create transaction: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to create transaction'], 500);
        }
    }

    public function deductMoneyFromZalo($id, $deductionMoney)
    {

        DB::beginTransaction();

        try {

            $user = UserZalo::find($id);
            if (!$user) {
                throw new Exception("User not found.");
            }

            if ($deductionMoney > $user->sub_wallet && $deductionMoney > $user->wallet) {
                Log::error('Số tiền trog cả hai ví không đủ để giao dịch.');
                return response()->json(['error' => 'Số tiền trog cả hai ví không đủ để giao dịch'], 422);
            }
            if ($deductionMoney <= $user->sub_wallet) {
                $user->sub_wallet -= $deductionMoney;
            } elseif ($deductionMoney <= $user->wallet) {
                $user->wallet -= $deductionMoney;
            }

            $user->save();

            DB::commit();
            return response()->json(['success' => 'Transaction completed successfully', 'user' => $user], 200);
        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Deduct money error: ' . $e->getMessage());
            return response()->json(['error' => 'Transaction failed'], 500);
        }
    }

    public function addZaloOa(Request $request)
    {
        Log::info('Receiving data from Admin ', $request->all());
        try {
            ZaloOa::create($request->all());
            Log::info('Zalo added to SuperAdmin Successfully');
            return response()->json(['success' => 'Thêm Zalo Super Admin thành công']);
        } catch (Exception $e) {
            Log::error('Failed to add zalo to Super Admin: ' . $e->getMessage());
            return response()->json(['error' => 'Thêm Zalo Super Admin thất bại']);
        }
    }
}
