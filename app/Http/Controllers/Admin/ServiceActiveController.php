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
        $users = User::where('role_id', '!=', 1)->get();
        if ($request->ajax()) {

            $data = Service::where('status', 'active')->where('type', 'cloud')
                // ->whereHas('order', function ($query) {
                //     $query->where('order_type', '!=', 2);
                // })
                ->select('*')->get();
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
                ->addColumn('another_column', function ($row) {
                    $user = User::where('email', $row->email)->first();
                    return '<button class="btn-transfer" data-email="'  . $user->full_name . ' (' . $row->email . ')' . '"  data-id="' . $row->id . '" data-hosting="' . $row->hosting->package_name . '" data-toggle="modal" data-target="#transferModal" title="Chuyển dữ liệu">
                                <i class="fas fa-exchange-alt"></i>
                            </button>';
                })
                ->editColumn('active_at', function ($row) {
                    $activeAt = Carbon::parse($row->active_at);
                    return $activeAt->format('d-m-Y');
                })
                ->addColumn('enddate', function ($row) {
                    $activeAt = Carbon::parse($row->active_at);
                    $expirationDate = $activeAt->addMonths($row->number);

                    if ($expirationDate->isPast()) {
                        $daysOverdue = $expirationDate->diffInDays(Carbon::now());
                        return $expirationDate->format('d-m-Y') . '<p class="endday">( Đã hết hạn - ' . $daysOverdue . ' ngày )</p>';
                    }

                    $daysLeft = $expirationDate->diffInDays(Carbon::now());
                    if ($daysLeft < 30) {
                        return $expirationDate->format('d-m-Y') . '<p class="endday">( Còn thời hạn ' . $daysLeft . ' ngày )</p>';
                    }

                    return $expirationDate->format('d-m-Y');
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
                ->editColumn('active_at', function ($row) {
                    return $row->active_at ? \Carbon\Carbon::parse($row->active_at)->format('d-m-Y') : null;
                })
                ->rawColumns(['active_at'])
                ->addColumn('action', function ($row) {
                    $user = User::where('email', $row->email)->first();
                    return '
                       <div class="d-flex ">
                        <div class="dropdown">
                        <!-- Icon hiển thị modal -->
                        <span style="font-size:26px; cursor:pointer; margin-right:15px" class="action"
                            onclick="openModal(' . $row->id . ')">
                            <i class="fas fa-cog"></i>
                        </span>
                    </div>

                    <button class="btn-transfer " data-email="'  . $user->full_name . ' (' . $row->email . ')' . '"  data-id="' . $row->id . '" data-hosting="' . $row->cloud->package_name . '" data-toggle="modal" data-target="#transferModal" title="Chuyển dữ liệu">
                            <i class="fas fa-exchange-alt"></i>
                        </button>
                       </div>
                    ';
                })->rawColumns(['action', 'giahan', 'enddate', 'packagename', 'active', 'user_info', 'another_column'])
                ->make(true);
        }
        $page = 'Quản lý dịch vụ Cloud';
        return view('backend.service.listcloud', compact('title', 'page', 'date', 'users'));
    }

    public function listhosting(Request $request, $date = null)
    {
        $title = "Quản lý dịch vụ Hosting";
        $users = User::where('role_id', '!=', 1)->get();
        if ($request->ajax()) {
            $data = Service::where('status', 'active')->where('type', 'hosting')
                // ->whereHas('order', function ($query) {
                //     $query->where('order_type', '!=', 2);
                // })
                ->select('*')->get();
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
                ->addColumn('another_column', function ($row) {
                    $user = User::where('email', $row->email)->first();
                    return '<button class="btn-transfer" data-email="'  . $user->full_name . ' (' . $row->email . ')' . '"  data-id="' . $row->id . '" data-hosting="' . $row->hosting->package_name . '" data-toggle="modal" data-target="#transferModal" title="Chuyển dữ liệu">
                                <i class="fas fa-exchange-alt"></i>
                            </button>';
                })
                ->addColumn('packagename', function ($row) {
                    $hosting = Hosting::find($row->product_id);
                    return $hosting->package_name;
                })
                ->editColumn('active_at', function ($row) {
                    $activeAt = Carbon::parse($row->active_at);
                    return $activeAt->format('d-m-Y');
                })
                ->addColumn('enddate', function ($row) {
                    $activeAt = Carbon::parse($row->active_at);
                    $expirationDate = $activeAt->addMonths($row->number);

                    if ($expirationDate->isPast()) {
                        $daysOverdue = $expirationDate->diffInDays(Carbon::now());
                        return $expirationDate->format('d-m-Y') . '<p class="endday">( Đã hết hạn -' . $daysOverdue . ' ngày )</p>';
                    }

                    $daysLeft = $expirationDate->diffInDays(Carbon::now());
                    if ($daysLeft < 30) {
                        return $expirationDate->format('d-m-Y') . '<p class="endday">( Còn thời hạn ' . $daysLeft . ' ngày )</p>';
                    }

                    return $expirationDate->format('d-m-Y');
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
                ->editColumn('active_at', function ($row) {
                    return $row->active_at ? \Carbon\Carbon::parse($row->active_at)->format('d-m-Y') : null;
                })
                ->rawColumns(['active_at'])
                ->editColumn('giahan', function ($row) {
                    return '<a href="' . route('order.show', $row->id) . '" class="btn btn-primary btn-sm edit"> Gia hạn </a>';
                })->rawColumns(['giahan'])
                ->addColumn('action', function ($row) {
                    $user = User::where('email', $row->email)->first();
                    return '
                    <div class="d-flex ">
                     <div class="dropdown">
                     <!-- Icon hiển thị modal -->
                     <span style="font-size:26px; cursor:pointer; margin-right:15px" class="action"
                         onclick="openModal(' . $row->id . ')">
                         <i class="fas fa-cog"></i>
                     </span>
                 </div>

                 <button class="btn-transfer " data-email="'  . $user->full_name . ' (' . $row->email . ')' . '"  data-id="' . $row->id . '" data-hosting="' . $row->cloud->package_name . '" data-toggle="modal" data-target="#transferModal" title="Chuyển dữ liệu">
                         <i class="fas fa-exchange-alt"></i>
                     </button>
                    </div>
                 ';
                })->rawColumns(['action', 'giahan', 'enddate', 'packagename', 'active', 'user_info', 'another_column'])
                ->make(true);
        }
        $page = 'Quản lý dịch vụ Hosting';
        return view('backend.service.listhosting', compact('title', 'page', 'date', 'users'));
    }

    public function listEmail(Request $request, $date = null)
    {
        $title = "Quản lý dịch vụ Email";
        $users = User::where('role_id', '!=', 1)->get();
        if ($request->ajax()) {
            $data = Service::where('status', 'active')->where('type', 'email')

                ->select('*')->get();
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
                ->addColumn('packagename', function ($row) {
                    $hosting = Email::find($row->product_id);
                    return $hosting->package_name;
                })
                ->addColumn('enddate', function ($row) {
                    $activeAt = Carbon::parse($row->active_at);
                    $expirationDate = $activeAt->addMonths($row->number);
                    if ($expirationDate->isPast()) {
                        $daysOverdue = $expirationDate->diffInDays(Carbon::now());
                        return $expirationDate->format('d-m-Y') . '<p class="endday">( Đã hết hạn -' . $daysOverdue . ' ngày )</p>';
                    }

                    $daysLeft = $expirationDate->diffInDays(Carbon::now());
                    if ($daysLeft < 30) {
                        return $expirationDate->format('d-m-Y') . '<p class="endday">( Còn thời hạn ' . $daysLeft . ' ngày )</p>';
                    }

                    return $expirationDate->format('d-m-Y');
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
                ->editColumn('active_at', function ($row) {
                    return $row->active_at ? \Carbon\Carbon::parse($row->active_at)->format('d-m-Y') : null;
                })
                ->editColumn('giahan', function ($row) {
                    return '<a href="' . route('order.show', $row->id) . '" class="btn btn-primary btn-sm edit"> Gia hạn </a>';
                })->rawColumns(['giahan'])
                ->addColumn('action', function ($row) {
                    $user = User::where('email', $row->email)->first();
                    return '
                    <div class="d-flex ">
                        <div class="dropdown">
                            <span style="font-size:26px; cursor:pointer; margin-right:15px" class="action"
                                onclick="openModal(' . $row->id . ')">
                                <i class="fas fa-cog"></i>
                            </span>
                        </div>

                        <button class="btn-transfer " data-email="'  . $user->full_name . ' (' . $row->email . ')' . '"  data-id="' . $row->id . '" data-hosting="' . $row->emailServer->package_name . '" data-toggle="modal" data-target="#transferModal" title="Chuyển dữ liệu">
                            <i class="fas fa-exchange-alt"></i>
                        </button>
                    </div>
                 ';
                })->rawColumns(['action', 'giahan', 'enddate', 'packagename', 'active', 'user_info', 'another_column'])
                ->make(true);
        }
        $page = 'Quản lý dịch vụ Email';
        return view('backend.service.listemail', compact('title', 'page', 'date', 'users'));
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


    public function addForm($type)
    {
        $title = "Thêm dịch vụ mới";
        $customers = User::where('role_id', 2)->get();
        $os_ids = Os::get();
        if ($type == 'email') {
            $packages = Email::get();
        } elseif ($type == 'cloud') {
            $packages = Cloud::get();
        } elseif ($type == 'hosting') {
            $packages = Hosting::get();
        }
        return view('backend.service.addservice', compact('type', 'title', 'customers', 'packages', 'os_ids'));
    }

    public function addSubmit(ServiceRequest $serviceRequest, $type)
    {

        $service = new Service();
        $service->email = $serviceRequest->email;
        $service->active_at = $serviceRequest->active_at;
        $service->number = $serviceRequest->end_date;
        $service->status = 'active';
        $service->type = $type;
        $service->price = 0;
        if ($type != 'domain') {
            $service->product_id = $serviceRequest->package_name;

            $domain = $serviceRequest->domain;
            $ten_mien = pathinfo($domain, PATHINFO_FILENAME);
            $duoi_mien = '.' . pathinfo($domain, PATHINFO_EXTENSION);
            $service->domain = $ten_mien;
            $service->domain_extension = $duoi_mien;
        }
        if ($type == 'email' || $type == 'hosting') {
            $service->domain = $serviceRequest->domain;
        }
        if ($type != 'cloud') {
            $service->os_id = $serviceRequest->os_id;
        }
        $service->save();

        return redirect()->back()->with('success', 'Thêm mới dịch vụ thành công!');
    }

    public function listhotel(Request $request, $date = null)
    {
        $title = "Quản lý dịch vụ Khách sạn";
        if ($request->ajax()) {
            $data = Service::where('status', 'active')->where('type', 'hotel')->orderBy('created_at', 'desc')
                ->select('*')->get();
            if ($date == 'expire_soon') {
                $data->whereRaw('DATEDIFF(DATE_ADD(active_at, INTERVAL number MONTH), NOW()) BETWEEN 1 AND 30');
            }
            if ($date == 'expire') {
                $data->whereRaw('DATEDIFF(DATE_ADD(active_at, INTERVAL number MONTH), NOW()) < 0');
            }
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('user_info', function ($row) {
                    $user = User::where('email', $row->email)->first();
                    return $user ? $user->full_name . '<br>(' . $user->phone_number . ')' : ''; // Nối tên và số điện thoại với dấu ngoặc và xuống dòng
                })

                ->editColumn('active_at', function ($row) {
                    $activeAt = Carbon::parse($row->active_at);
                    return $activeAt->format('d-m-Y');
                })

                ->addColumn('enddate', function ($row) {
                    $activeAt = Carbon::parse($row->active_at);
                    $expirationDate = $activeAt->addMonths($row->number);

                    if ($expirationDate->isPast()) {
                        $daysOverdue = $expirationDate->diffInDays(Carbon::now());
                        return $expirationDate->format('d-m-Y') . '<p class="endday">( Đã hết hạn - ' . $daysOverdue . ' ngày )</p>';
                    }

                    $daysLeft = $expirationDate->diffInDays(Carbon::now());
                    if ($daysLeft < 30) {
                        return $expirationDate->format('d-m-Y') . '<p class="endday">( Còn thời hạn ' . $daysLeft . ' ngày )</p>';
                    }

                    return $expirationDate->format('d-m-Y');
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

    public function transferService(Request $request)
    {

        // dd($request->all());
        $service = Service::find($request->service_id);
        $service->update([
            'email' => $request->username
        ]);
        return response()->json(['message' => 'Chuyển domain thành công!']);
    }
}
