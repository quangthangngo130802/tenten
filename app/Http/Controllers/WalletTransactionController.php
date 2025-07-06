<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\WalletTransaction;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class WalletTransactionController extends Controller
{
    public function index(Request $request, $status = null)
    {
        $title = "Danh sách lịch sử nạp tiền";
        $user = Auth::user(); // Lấy thông tin người dùng hiện tại

        if ($request->ajax()) {
            $data = WalletTransaction::with('user')
                ->select('*')
                ->orderBy('created_at', 'desc');
            if ($user->role_id != 1) {
                $data = $data->where('user_id', $user->id);
            }
            return DataTables::of($data)
                ->addIndexColumn()
                ->filterColumn('user_name', function ($query, $keyword) {
                    $query->whereHas('user', function ($q) use ($keyword) {
                        $q->where('full_name', 'like', "%$keyword%")
                            ->orWhere('email', 'like', "%$keyword%")
                            ->orWhere('phone_number',  $keyword);
                    });
                })
                ->editColumn('approved_at', function ($row) {
                    return Carbon::parse($row->approved_at)->format('d-m-Y');
                })
                ->addColumn('user_name', function ($row) {
                    $user = User::find($row->user_id);
                    return $user && !empty($user->full_name) && !empty($user->phone_number)
                        ? $user->full_name . '<br>(' . $user->phone_number . ')'
                        : ($user && !empty($user->full_name) ? $user->full_name : '');
                })
                ->editColumn('amount', function ($row) {

                    return '<span>' . number_format($row->amount) . ' đ' . '</span>';
                })
                ->editColumn('detail', function ($row) {
                    return '<a href="' . route('order.show', $row->id) . '" class="btn btn-primary btn-sm edit"> Chi tiết </a>';
                })
                ->addColumn('action', function ($row) use ($user) {
                    if ($user->role_id != 1) {
                        if ($row->status == 'pending') {
                            return '<span class="badge" style="background-color: orange; color: white;">Chờ duyệt</span>';
                        } elseif ($row->status == 'approved') {
                            return '<span class="badge" style="background-color: green; color: white;">Đã duyệt</span>';
                        } elseif ($row->status == 'rejected') {
                            return '<span class="badge" style="background-color: red; color: white;">Đã hủy</span>';
                        } else {
                            return '<span class="badge badge-secondary">Không xác định</span>';
                        }
                    }
                    if ($row->status == 'pending') {
                        return '
                            <button type="button" class="btn btn-primary btn-sm" onclick="openApproveModal(' . $row->id . ')">
                                Duyệt / Hủy
                            </button>
                        ';
                    } elseif ($row->status == 'approved') {
                        return '<span class="badge badge-success">Đã duyệt</span>';
                    } elseif ($row->status == 'rejected') {
                        return '<span class="badge badge-danger">Đã hủy</span>';
                    } else {
                        return '<span class="badge badge-secondary">Không xác định</span>';
                    }
                })
                ->rawColumns(['action', 'detail', 'amount', 'user_name'])
                ->make(true);
        }
        $page = 'Lịch sử nạp tiền ';
        return view('backend.history.transaction', compact('title', 'page'));
    }

    public function approve(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'status' => 'required|in:approved,rejected',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Dữ liệu không hợp lệ.',
                'errors' => $validator->errors()
            ], 422);
        }

        $transaction = WalletTransaction::find($id);

        if (!$transaction) {
            return response()->json([
                'message' => 'Giao dịch không tồn tại.'
            ], 404);
        }


        $transaction->status = $request->input('status');
        $transaction->approved_at = now();
        $transaction->save();

        $user = User::find($transaction->user_id);
        if($request->input('status') == 'approved') {
            $user->wallet = $user->wallet + $transaction->amount;
            $user->save();
        }

        return response()->json([
            'message' => 'Cập nhật trạng thái thành công.',
            'data' => $transaction
        ]);
    }
}
