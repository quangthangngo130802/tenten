<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Cloud;
use App\Models\Email;
use App\Models\Hosting;
use App\Models\OrderDetail;
use App\Models\Service;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\DataTables;

class CustomerServiceController extends Controller
{


    public function listhosting(Request $request, $date = null)
    {
        $title = "Quản lý dịch vụ Hosting";
        $email = Auth::user()->email;
        if ($request->ajax()) {
            $data = Service::where('status', 'active')->where('type', 'hosting')
                // ->whereHas('order', function ($query) {
                //     $query->where('order_type', '!=', 2);
                // })
                ->select('*');
            if ($date == 'expire_soon') {
                $data->whereRaw('DATEDIFF(DATE_ADD(active_at, INTERVAL number MONTH), NOW()) BETWEEN 1 AND 30');
            }
            if ($date == 'expire') {
                $data->whereRaw('DATEDIFF(DATE_ADD(active_at, INTERVAL number MONTH), NOW()) < 0');
            }
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('packagename', function ($row) {
                    $hosting = Hosting::find($row->product_id);
                    return $hosting->package_name;
                })
                ->addColumn('enddate', function ($row) {
                    $activeAt = Carbon::parse($row->active_at);
                    $expirationDate = $activeAt->addMonths($row->number);

                    if ($expirationDate->isPast()) {
                        $daysOverdue = $expirationDate->diffInDays(Carbon::now());
                        return $expirationDate->format('Y-m-d') . '<p class="endday">( Đã hết hạn - ' . $daysOverdue . ' ngày )</p>';
                    }

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
                    return '<form action="' . route('customer.cart.addrenews', $row->id) . '" method="POST" style="display: inline;">
                                ' . csrf_field() . '
                                <button type="submit" class="btn btn-primary btn-sm edit">Gia hạn</button>
                            </form>';
                })->rawColumns(['giahan'])
                ->addColumn('action', function ($row) {
                    return '
                        <div class="dropdown">
                            <!-- Icon hiển thị modal -->
                            <span style="font-size:26px; cursor:pointer;" class="action"
                                onclick="openModal(' . $row->id . ')">
                                <i class="fas fa-cog"></i>
                            </span>
                        </div>';
                })->rawColumns(['action', 'giahan', 'enddate', 'packagename', 'active'])
                ->make(true);
        }
        $page = 'Quản lý dịch vụ Hosting';
        return view('customer.service.listhosting', compact('title', 'page', 'date'));
    }

    public function listServices(Request $request, $type, $date = null)
    {
        $title = "Quản lý dịch vụ " . ucfirst($type);
        $email = Auth::user()->email;

        if ($request->ajax()) {
            $data = Service::where('status', 'active')->where('type', $type)->select('*');

            if ($date == 'expire_soon') {
                $data->whereRaw('DATEDIFF(DATE_ADD(active_at, INTERVAL number MONTH), NOW()) BETWEEN 1 AND 30');
            }

            if ($date == 'expire') {
                $data->whereRaw('DATEDIFF(DATE_ADD(active_at, INTERVAL number MONTH), NOW()) < 0');
            }

            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('packagename', function ($row) use ($type) {
                    $model = null;

                    if ($type === 'cloud') {
                        $model = Cloud::find($row->product_id);
                    } elseif ($type === 'hosting') {
                        $model = Hosting::find($row->product_id);
                    } elseif ($type === 'email') {
                        $model = Email::find($row->product_id);
                    }

                    return $model ? $model->package_name : 'N/A';
                })
                ->addColumn('enddate', function ($row) {
                    $activeAt = Carbon::parse($row->active_at);
                    $expirationDate = $activeAt->addMonths($row->number);

                    if ($expirationDate->isPast()) {
                        $daysOverdue = $expirationDate->diffInDays(Carbon::now());
                        return $expirationDate->format('Y-m-d') . '<p class="endday">( Đã hết hạn - ' . $daysOverdue . ' ngày )</p>';
                    }

                    $daysLeft = $expirationDate->diffInDays(Carbon::now());
                    if ($daysLeft < 30) {
                        return $expirationDate->format('Y-m-d') . '<p class="endday">( Còn thời hạn ' . $daysLeft . ' ngày )</p>';
                    }

                    return $expirationDate->format('Y-m-d');
                })->rawColumns(['enddate'])
                ->editColumn('active', function ($row) {
                    if ($row->status == 'active') {
                        return '<div class="status active">
                                <span class="icon-check" style="margin-right:5px"></span> Hoạt động
                            </div>';
                    } else {
                        return '<div class="status paused">
                                <span class="icon-warning" style="margin-right:5px"></span> Tạm dừng
                            </div>';
                    }
                })->rawColumns(['active'])
                ->editColumn('giahan', function ($row) {
                    return '<form action="' . route('customer.cart.addrenews', $row->id) . '" method="POST" style="display: inline;">
                            ' . csrf_field() . '
                            <button type="submit" class="btn btn-primary btn-sm edit">Gia hạn</button>
                        </form>';
                })->rawColumns(['giahan'])
                ->addColumn('action', function ($row) {
                    return '
                    <div class="dropdown">
                        <!-- Icon hiển thị modal -->
                        <span style="font-size:26px; cursor:pointer;" class="action"
                            onclick="openModal(' . $row->id . ')">
                            <i class="fas fa-cog"></i>
                        </span>
                    </div>';
                })->rawColumns(['action', 'giahan', 'enddate', 'packagename', 'active'])
                ->make(true);
        }

        $page = "Quản lý dịch vụ " . ucfirst($type);
        return view('customer.service.list', compact('title', 'page', 'type', 'date'));
    }
}
