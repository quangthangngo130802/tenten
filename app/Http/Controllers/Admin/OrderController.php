<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Yajra\DataTables\DataTables;

class OrderController extends Controller
{
    public function index(Request $request, $status = null)
    {
        $title = "Đơn hàng";

        if ($request->ajax()) {
            $data = Order::where('status', $status)->select('*');
            return DataTables::of($data)
                ->editColumn('created_at', function ($row) {
                    return Carbon::parse($row->created_at)->format('Y-m-d H:i:s');
                })
                ->editColumn('amount', function ($row) {
                    return number_format($row->amount);
                })
                ->editColumn('status', function ($row) {
                    return $row->status == 'payment'
                    ? '<span style="color: green;">Đã thanh toán</span>'
                    : ($row->status == 'pending'
                        ? '<span style="color: orange;">Chờ duyệt</span>'
                        : '<span style="color: red;">Chưa thanh toán</span>');
                })
                ->editColumn('payment', function ($row) {
                    return number_format($row->payment);
                })
                ->editColumn('detail', function ($row) {
                    return '<a href="' . route('order.show', $row->id) . '" class="btn btn-primary btn-sm edit"> Chi tiết </a>';
                })->rawColumns(['detail'])
                ->addColumn('action', function ($row) {
                    return '<div style="display: flex;">
                                <a href="#" class="btn btn-danger btn-sm delete"
                                    onclick="event.preventDefault(); document.getElementById(\'delete-form-' . $row->id . '\').submit();">
                                    <i class="fas fa-trash btn-delete" title="Xóa"></i>
                                </a>
                                <form id="delete-form-' . $row->id . '" action="' . route('order.delete', $row->id) . '" method="POST" style="display:none;">
                                    ' . csrf_field() . '

                                </form>
                            </div>';
                })->rawColumns(['action', 'detail', 'status'])
                ->make(true);
        }
        $page = 'Đơn hàng';
        return view('backend.order.index', compact('title', 'page'));
    }

    public function show($id)
    {
        $order = Order::findOrFail($id);
        return view('backend.order.show', compact('order'));
    }
}
