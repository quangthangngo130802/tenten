<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;

class OrderController extends Controller
{
    public function index(Request $request, $status = null)
    {
        $title = "Đơn hàng";

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
                    return number_format($row->payment);
                })
                ->editColumn('detail', function ($row) {
                    return '<a href="' . route('customer.order.show', $row->id) . '" class="btn btn-primary btn-sm edit"> Chi tiết </a>';
                })->rawColumns(['detail'])
                ->rawColumns(['detail','status'])
                ->make(true);
        }
        $page = 'Đơn hàng';
        return view('customer.order.index', compact('title', 'page'));
    }

    public function show($id)
    {
        $order = Order::findOrFail($id);
        return view('customer.order.show', compact('order'));
    }
}
