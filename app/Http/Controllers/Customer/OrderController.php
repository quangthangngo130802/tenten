<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\TransactionHistory;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Yajra\DataTables\Facades\DataTables;

class OrderController extends Controller
{
    public function index(Request $request, $status = null)
    {
        $title = "Danh sách đơn hàng";

        if ($request->ajax()) {
            $data = Order::where('status', $status)->where('email', Auth::user()->email)->select('*');
            return DataTables::of($data)
                ->editColumn('created_at', function ($row) {
                    return Carbon::parse($row->created_at)->format('Y-m-d H:i:s');
                })
                ->editColumn('status', function ($row) {
                    return $row->status == 'payment'
                    ? '<span style="color: green;">Đã thanh toán</span>'
                    : ($row->status == 'pending'
                        ? '<span style="color: orange;">Chờ duyệt</span>'
                        : '<span style="color: red;">Chưa thanh toán</span>');
                })
                ->editColumn('amount', function ($row) {
                    return number_format($row->amount);
                })
                ->editColumn('payment', function ($row) {
                    return $row->status == 'payment'
                    ? '<span style="color: green;">Đã thanh toán</span>'
                    : ($row->status == 'pending'
                        ? '<span style="color: orange;">Chờ duyệt</span>'
                        : '<a data-id=' .$row->id. ' class="btn btn-primary btn-sm clickpayment"> Thanh toán</a>');
                })
                ->editColumn('detail', function ($row) {
                    return '<a href="' . route('customer.order.show', $row->id) . '" class=" btn-sm edit"> Chi tiết </a>';
                })->rawColumns(['detail'])
                ->rawColumns(['detail','status', 'payment'])
                ->make(true);
        }
        $page = 'Đơn hàng';
        return view('customer.order.index', compact('title', 'page'));
    }

    public function show($id)
    {
        $title = "Chi tiết đơn hàng";
        $page = "Đơn hàng";
        $order = Order::findOrFail($id);
        return view('customer.order.show', compact('order','title', 'page'));

    }

    public function payment(Request $request)
    {
        /**
         * @var User $user
         */
        $user = Auth::user();
        $order = Order::find($request->id);

        if ($user->wallet < $order->amount) {
            return response()->json([
                'error' => 'Tài khoản không đủ để thanh toán!',
            ], 400);
        }
        $user->update([
            'wallet' => $user->wallet - $order->amount,
        ]);
        $order->orderDetail()->update(['active' => 'payment']);
        $order->status = 'payment';
        $order->save();

        TransactionHistory::create([
            'code' => $order->code,
            'user_id' => $user->id,
            'type' => 'Thanh toán',
            'amount' => $order->amount,
            'status' => 2,
            'type' => 2, /// 1 chưa, 2 thanh toán, 3 duỵet
            'description' => 'Thanh toán đơn hàng #' . $order->code,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Thanh toán thành công!',
        ]);
    }
}
