<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\TransactionHistory;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;

class TransactionHistoryController extends Controller
{
    public function index(Request $request, $status = null)
    {
        $title = "Danh sách lịch sử";
        $user = Auth::user(); // Lấy thông tin người dùng hiện tại

        if ($request->ajax()) {
            $data = TransactionHistory::with('user')
                ->select('transaction_histories.*');
            if ($user->role_id != 1) {
                $data = $data->where('user_id', $user->id);
            }
            return DataTables::of($data)
                ->addIndexColumn()
                ->filterColumn('user_name', function ($query, $keyword) {
                    $query->whereHas('user', function ($q) use ($keyword) {
                        $q->where('full_name', 'like', "%$keyword%")
                            ->orWhere('email', 'like', "%$keyword%");
                    });
                })
                ->editColumn('created_at', function ($row) {
                    return Carbon::parse($row->created_at)->format('Y-m-d H:i:s');
                })
                ->editColumn('user_id', function ($row) {
                    $user = User::where('email', $row->email)->first();
                    return $user && !empty($user->full_name) && !empty($user->phone_number)
                        ? $user->full_name . '<br>(' . $user->phone_number . ')'
                        : ($user && !empty($user->full_name) ? $user->full_name : '');
                })
                ->editColumn('amount', function ($row) {
                    if ($row->status == 1) {
                        return '<span style="color: green;">' . number_format($row->amount) . '</span>';
                    } else {
                        return '<span style="color: red;">- ' . number_format($row->amount) . '</span>';
                    }
                })
                ->editColumn('detail', function ($row) {
                    return '<a href="' . route('order.show', $row->id) . '" class="btn btn-primary btn-sm edit"> Chi tiết </a>';
                })
                ->addColumn('action', function ($row) use ($user) {
                    if ($user->role_id != 1) {
                        return '';
                    }
                    return '<div style="display: flex;">
                            <a href="#" class="btn btn-danger btn-sm delete"
                            onclick="confirmDelete(event, ' . $row->id . ')">
                               <i class="fas fa-trash btn-delete" title="Xóa"></i>
                            </a>
                            <form id="delete-form-' . $row->id . '" action="' . route('history.delete', $row->id) . '" method="POST" style="display:none;">
                                ' . csrf_field() . '
                            </form>
                        </div>';
                })
                ->rawColumns(['action', 'detail', 'amount', 'user_id'])
                ->make(true);
        }
        $page = 'Lịch sử ';
        return view('backend.history.index', compact('title', 'page'));
    }
    public function delete($id){
        $order = TransactionHistory::find($id);
        $order->delete();
        return redirect()->back()->with('success', 'Đã xóa thành công');
    }
}
