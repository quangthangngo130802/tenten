<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Cloud;
use App\Models\Hosting;
use App\Models\Order;
use App\Models\OrderDetail;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class ServiceActiveController extends Controller
{
    //
    public function listcloud(Request $request)
    {
        $title = "Quản lý dịch vụ Cloud";

        if ($request->ajax()) {
            $data = OrderDetail::where('status', 'active')->where('type', 'cloud')
            ->whereHas('order', function ($query) {
                $query->where('order_type', '!=', 2);
            })->select('*');
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('packagename', function ($row) {
                    $cloud = Cloud::find($row->product_id);
                    return $cloud->package_name . ' - ' . $row->os->name;
                })
                ->addColumn('enddate', function ($row) {
                    $activeAt = Carbon::parse($row->active_at);
                    $expirationDate = $activeAt->addMonths($row->number);

                    $daysLeft = $expirationDate->diffInDays(Carbon::now());
                    if ($daysLeft < 30) {
                        return $expirationDate->format('Y-m-d') . '<p class="endday">( Còn thời hạn ' . $daysLeft . ' ngày )</p>';
                    }

                    return $expirationDate->format('Y-m-d');
                })->rawColumns(['enddate'])
                ->editColumn('active', function ($row) {
                    if ($row->status == 'active') {
                        return '<div class="status active">
                                    <span class="icon-check"></span> Hoạt động
                                </div>';
                    } else {
                        return '<div class="status paused">
                                    <span class="icon-warning"></span> Tạm dừng
                                </div>';
                    }
                })->rawColumns(['active'])
                ->editColumn('giahan', function ($row) {
                    return '<a href="' . route('order.show', $row->id) . '" class="btn btn-primary btn-sm edit"> Gia hạn </a>';
                })->rawColumns(['giahan'])
                ->addColumn('action', function ($row) {
                    return '
                            <div class="dropdown">
                                <span style="font-size:26px; cursor:pointer;" class="action">
                                    <i class="fas fa-cog"></i>
                                </span>
                                <div class="dropdown-menu menu-action">
                                    <a class="dropdown-item" href="#">Rao bán tên miền</a>
                                    <a class="dropdown-item" href="#">Cài đặt NS</a>
                                    <a class="dropdown-item" href="#">Cài đặt DNS</a>
                                    <a class="dropdown-item" href="#">Gửi email xác thực</a>
                                    <a class="dropdown-item" href="#">Thay đổi mật khẩu</a>
                                    <a class="dropdown-item" href="#">Chi tiết tên miền</a>
                                    <a class="dropdown-item" href="#">Download bản khai</a>
                                </div>
                            </div>';
                })->rawColumns(['action', 'giahan', 'enddate', 'packagename', 'active'])
                ->make(true);
        }
        $page = 'Quản lý dịch vụ Cloud';
        return view('backend.service.listcloud', compact('title', 'page'));
    }

    public function listhosting(Request $request)
    {
        $title = "Quản lý dịch vụ Hosting";

        if ($request->ajax()) {
            $data = OrderDetail::where('status', 'active')->where('type', 'hosting')
            ->whereHas('order', function ($query) {
                $query->where('order_type', '!=', 2);
            })->select('*');
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('packagename', function ($row) {
                    $hosting = Hosting::find($row->product_id);
                    return $hosting->package_name;
                })
                ->addColumn('enddate', function ($row) {
                    $activeAt = Carbon::parse($row->active_at);
                    $expirationDate = $activeAt->addMonths($row->number);

                    $daysLeft = $expirationDate->diffInDays(Carbon::now());
                    if ($daysLeft < 30) {
                        return $expirationDate->format('Y-m-d') . '<p class="endday">( Còn thời hạn ' . $daysLeft . ' ngày )</p>';
                    }

                    return $expirationDate->format('Y-m-d');
                })->rawColumns(['enddate'])
                ->editColumn('active', function ($row) {
                    if ($row->status == 'active') {
                        return '<div class="status active">
                                    <span class="icon-check"></span> Hoạt động
                                </div>';
                    } else {
                        return '<div class="status paused">
                                    <span class="icon-warning"></span> Tạm dừng
                                </div>';
                    }
                })->rawColumns(['active'])
                ->editColumn('giahan', function ($row) {
                    return '<a href="' . route('order.show', $row->id) . '" class="btn btn-primary btn-sm edit"> Gia hạn </a>';
                })->rawColumns(['giahan'])
                ->addColumn('action', function ($row) {
                    return '<div class="dropdown">
                                <span style="font-size:26px; cursor:pointer;" class="action">
                                    <i class="fas fa-cog"></i>
                                </span>
                                <div class="dropdown-menu menu-action">
                                    <a class="dropdown-item" href="#">Rao bán tên miền</a>
                                    <a class="dropdown-item" href="#">Cài đặt NS</a>
                                    <a class="dropdown-item" href="#">Cài đặt DNS</a>
                                    <a class="dropdown-item" href="#">Gửi email xác thực</a>
                                    <a class="dropdown-item" href="#">Thay đổi mật khẩu</a>
                                    <a class="dropdown-item" href="#">Chi tiết tên miền</a>
                                    <a class="dropdown-item" href="#">Download bản khai</a>
                                </div>
                            </div>';
                })->rawColumns(['action', 'giahan', 'enddate', 'packagename', 'active'])
                ->make(true);
        }
        $page = 'Quản lý dịch vụ Hosting';
        return view('backend.service.listhosting', compact('title', 'page'));
    }
}
