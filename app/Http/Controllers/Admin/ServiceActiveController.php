<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\ServiceRequest;
use App\Models\Cloud;
use App\Models\Email;
use App\Models\Hosting;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Os;
use App\Models\Service;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class ServiceActiveController extends Controller
{
    //
    public function listcloud(Request $request, $date = null)
    {
        $title = "Quản lý dịch vụ Cloud";
        if ($request->ajax()) {
            $data = Service::where('status', 'active')->where('type', 'cloud')
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
                ->addColumn('user_info', function ($row) {
                    // Kiểm tra nếu có liên kết với user qua order
                    // if ($row->order) {
                    //     return $row->order->fullname . ' <p> (' . $row->order->email . ')</p>';
                    // }
                    // return 'N/A'; // Nếu không có thông tin
                    return $row->email;
                })
                ->addColumn('packagename', function ($row) {
                    $cloud = Cloud::find($row->product_id);
                    return $cloud->package_name . ' - ' . $row->os->name;
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
                    return '<a href="' . route('order.show', $row->id) . '" class="btn btn-primary btn-sm edit"> Gia hạn </a>';
                })->rawColumns(['giahan'])
                ->addColumn('action', function ($row) {
                    return '
                        <div class="dropdown">
                            <!-- Icon hiển thị modal -->
                            <span style="font-size:26px; cursor:pointer;" class="action"
                                onclick="openModal(' . $row->id . ')">
                                <i class="fas fa-cog"></i>
                            </span>
                        </div>
                    ';
                })->rawColumns(['action', 'giahan', 'enddate', 'packagename', 'active', 'user_info'])
                ->make(true);
        }
        $page = 'Quản lý dịch vụ Cloud';
        return view('backend.service.listcloud', compact('title', 'page', 'date'));
    }

    public function listhosting(Request $request, $date = null)
    {
        $title = "Quản lý dịch vụ Hosting";

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
                ->addColumn('user_info', function ($row) {
                    // Kiểm tra nếu có liên kết với user qua order
                    // if ($row->order) {
                    //     return $row->order->fullname . ' <p> (' . $row->order->email . ')</p>';
                    // }
                    // return 'N/A'; // Nếu không có thông tin
                    return $row->email;
                })
                ->addColumn('packagename', function ($row) {
                    $hosting = Hosting::find($row->product_id);
                    return $hosting->package_name;
                })
                ->addColumn('enddate', function ($row) {
                    $activeAt = Carbon::parse($row->active_at);
                    $expirationDate = $activeAt->addMonths($row->number);

                    if ($expirationDate->isPast()) {
                        $daysOverdue = $expirationDate->diffInDays(Carbon::now());
                        return $expirationDate->format('Y-m-d') . '<p class="endday">( Đã hết hạn -' . $daysOverdue . ' ngày )</p>';
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
                    return '<a href="' . route('order.show', $row->id) . '" class="btn btn-primary btn-sm edit"> Gia hạn </a>';
                })->rawColumns(['giahan'])
                ->addColumn('action', function ($row) {
                    return ' <div class="dropdown">
                                <!-- Icon hiển thị modal -->
                                <span style="font-size:26px; cursor:pointer;" class="action"
                                    onclick="openModal(' . $row->id . ')">
                                    <i class="fas fa-cog"></i>
                                </span>
                            </div>';
                })->rawColumns(['action', 'giahan', 'enddate', 'packagename', 'active', 'user_info'])
                ->make(true);
        }
        $page = 'Quản lý dịch vụ Hosting';
        return view('backend.service.listhosting', compact('title', 'page', 'date'));
    }

    public function getContentService($id)
    {


        $service = Service::find($id);
        // dd($service);
        $content = $service->content;

        // Trả về dữ liệu dưới dạng JSON
        return response()->json(['content' => $content]);
    }

    // Controller method to save content
    public function saveContent(Request $request)
    {
        // Lấy ID và nội dung từ request
        $content = $request->input('content');
        $id = $request->input('id');

        // Tìm bài viết theo ID và cập nhật nội dung
        $service = Service::find($id);
        if ($service) {
            $service->content = $content;
            $service->save();
            return response()->json(['success' => true]);
        }

        return response()->json(['success' => false, 'message' => 'service not found']);
    }


    public function addForm($type){
        $title = "Thêm dịch vụ mới";
        $customers = User::where('role_id', 2)->get();
        $os_ids = Os::get();
        if($type == 'email'){
            $packages = Email::get();
        }elseif($type == 'cloud'){
            $packages = Cloud::get();
        }elseif($type == 'hosting'){
            $packages = Hosting::get();
        }
        return view('backend.service.addservice', compact('type', 'title', 'customers','packages', 'os_ids'));
    }

    public function addSubmit(ServiceRequest $serviceRequest , $type)
    {

        $service = new Service();
        $service->email = $serviceRequest->email;
        $service->active_at = $serviceRequest->active_at;
        $service->number = $serviceRequest->end_date;
        $service->status = 'active';
        $service->type = $type;
        $service->price = 0;
        if($type != 'domain'){
            $service->product_id = $serviceRequest->package_name;

            $domain = $serviceRequest->domain;
            $ten_mien = pathinfo($domain, PATHINFO_FILENAME);
            $duoi_mien = '.' . pathinfo($domain, PATHINFO_EXTENSION);
            $service->domain = $ten_mien;
            $service->domain_extension = $duoi_mien;
        }
        if($type == 'email' || $type == 'hosting'){
            $service->domain = $serviceRequest->domain;
        }
        if($type != 'cloud'){
            $service->os_id = $serviceRequest->os_id;
        }
        $service->save();

        return redirect()->back()->with('success', 'Thêm mới dịch vụ thành công!');


    }

    public function listhotel(Request $request, $date = null)
    {
        $title = "Quản lý dịch vụ Khách sạn";
        if ($request->ajax()) {
            $data = Service::where('status', 'active')->where('type', 'hotel')
                ->select('*');
            if ($date == 'expire_soon') {
                $data->whereRaw('DATEDIFF(DATE_ADD(active_at, INTERVAL number MONTH), NOW()) BETWEEN 1 AND 30');
            }
            if ($date == 'expire') {
                $data->whereRaw('DATEDIFF(DATE_ADD(active_at, INTERVAL number MONTH), NOW()) < 0');
            }
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('user_info', function ($row) {
                    return $row->email;
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
                    return '<a href="' . route('order.show', $row->id) . '" class="btn btn-primary btn-sm edit"> Gia hạn </a>';
                })->rawColumns(['giahan'])
                ->addColumn('action', function ($row) {
                    return '
                        <div class="dropdown">
                            <!-- Icon hiển thị modal -->
                            <span style="font-size:26px; cursor:pointer;" class="action"
                                onclick="openModal(' . $row->id . ')">
                                <i class="fas fa-cog"></i>
                            </span>
                        </div>
                    ';
                })->rawColumns(['action', 'giahan', 'enddate', 'active', 'user_info'])
                ->make(true);
        }
        $page = 'Quản lý dịch vụ khách sạn';
        return view('backend.service.listhotel', compact('title', 'page', 'date'));
    }
}
