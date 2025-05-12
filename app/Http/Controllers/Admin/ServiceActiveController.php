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
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;

class ServiceActiveController extends Controller
{
    //
    public function listcloud(Request $request, $date = null)
    {
        $title = "Quản lý dịch vụ Cloud";
        $users = User::where('role_id', '!=', 1)->get();

        if ($request->ajax()) {

            $data = Service::where('type', 'cloud')->select('*')->get();
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
                    return $row->email . '<br>' . $user->full_name . ' (' . $user->phone_number . ' )';
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
                        return $expirationDate->format('d-m-Y') . '<p class="endday">(  - ' . $daysOverdue . ' ngày )</p>';
                    }

                    $daysLeft = $expirationDate->diffInDays(Carbon::now());
                    if ($daysLeft < 30) {
                        return $expirationDate->format('d-m-Y') . '<p class="endday">(  ' . $daysLeft . ' ngày )</p>';
                    }

                    return $expirationDate->format('d-m-Y');
                })->rawColumns(['enddate'])
                ->editColumn('active', function ($row) {
                    return ' <div class="toggle-container justify-content-center">
                    <label class="switch">
                        <input type="checkbox" class="toggleStatus" data-id="' . $row->id . '"' . ($row->status == 'active' ? ' checked' : '') . '>
                        <span class="slider"></span>
                    </label>
                </div>';
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
                    <div class="d-flex">
                    <div class="dropdown">
                        <!-- Icon hiển thị modal -->
                        <span style="font-size:26px; cursor:pointer; margin-right:15px" class="action" onclick="toggleMenu(\'' . $row->id . '\')">
                            <i class="fas fa-cog"></i>
                        </span>

                        <!-- Menu Dropdown -->
                        <div id="menu-' . $row->id . '" class="dropdown-menu">
                            <ul>
                                <li><a href="#" onclick="openModal(' . $row->id . ')">Nội dung</a></li>

                                <li><a href="#" onclick="openModalGiaHan(' . $row->id . ')">Gia hạn</a></li>
                            </ul>
                        </div>
                    </div>

                    <!-- Button chuyển dữ liệu -->
                    <button class="btn-transfer"
                            data-email="' . $user->full_name . ' (' . $row->email . ')"
                            data-id="' . $row->id . '"
                            data-hosting="' . $row->cloud->package_name . '"
                            data-toggle="modal"
                            data-target="#transferModal"
                            title="Chuyển dữ liệu">
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
            $data = Service::where('type', 'hosting')
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
                        return $expirationDate->format('d-m-Y') . '<p class="endday">(  -' . $daysOverdue . ' ngày )</p>';
                    }

                    $daysLeft = $expirationDate->diffInDays(Carbon::now());
                    if ($daysLeft < 30) {
                        return $expirationDate->format('d-m-Y') . '<p class="endday">(  ' . $daysLeft . ' ngày )</p>';
                    }

                    return $expirationDate->format('d-m-Y');
                })->rawColumns(['enddate'])
                ->editColumn('active', function ($row) {
                    return ' <div class="toggle-container justify-content-center">
                    <label class="switch">
                        <input type="checkbox" class="toggleStatus" data-id="' . $row->id . '"' . ($row->status == 'active' ? ' checked' : '') . '>
                        <span class="slider"></span>
                    </label>
                </div>';
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
                    <div class="d-flex">
                    <div class="dropdown">
                        <!-- Icon hiển thị modal -->
                        <span style="font-size:26px; cursor:pointer; margin-right:15px" class="action" onclick="toggleMenu(\'' . $row->id . '\')">
                            <i class="fas fa-cog"></i>
                        </span>

                        <!-- Menu Dropdown -->
                        <div id="menu-' . $row->id . '" class="dropdown-menu">
                            <ul>
                                <li><a href="#" onclick="openModal(' . $row->id . ')">Nội dung</a></li>

                                 <li><a href="#" onclick="openModalGiaHan(' . $row->id . ')">Gia hạn</a></li>
                            </ul>
                        </div>
                    </div>

                    <!-- Button chuyển dữ liệu -->
                    <button class="btn-transfer"
                            data-email="' . $user->full_name . ' (' . $row->email . ')"
                            data-id="' . $row->id . '"
                            data-hosting="' . $row->cloud->package_name . '"
                            data-toggle="modal"
                            data-target="#transferModal"
                            title="Chuyển dữ liệu">
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
            $data = Service::where('type', 'email')

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
                        return $expirationDate->format('d-m-Y') . '<p class="endday">(  -' . $daysOverdue . ' ngày )</p>';
                    }

                    $daysLeft = $expirationDate->diffInDays(Carbon::now());
                    if ($daysLeft < 30) {
                        return $expirationDate->format('d-m-Y') . '<p class="endday">(  ' . $daysLeft . ' ngày )</p>';
                    }

                    return $expirationDate->format('d-m-Y');
                })->rawColumns(['enddate'])
                ->editColumn('active', function ($row) {
                    return ' <div class="toggle-container justify-content-center">
                    <label class="switch">
                        <input type="checkbox" class="toggleStatus" data-id="' . $row->id . '"' . ($row->status == 'active' ? ' checked' : '') . '>
                        <span class="slider"></span>
                    </label>
                </div>';
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
                    <div class="d-flex">
                        <div class="dropdown">
                            <!-- Icon hiển thị modal -->
                            <span style="font-size:26px; cursor:pointer; margin-right:15px" class="action" onclick="toggleMenu(\'' . $row->id . '\')">
                                <i class="fas fa-cog"></i>
                            </span>

                            <!-- Menu Dropdown -->
                            <div id="menu-' . $row->id . '" class="dropdown-menu">
                                <ul>
                                    <li><a href="#" onclick="openModal(' . $row->id . ')">Nội dung</a></li>

                                    <li><a href="#" onclick="openModalGiaHan(' . $row->id . ')">Gia hạn</a></li>
                                </ul>
                            </div
                        </div>

                        <!-- Button chuyển dữ liệu -->
                        <button class="btn-transfer"
                                data-email="' . $user->full_name . ' (' . $row->email . ')"
                                data-id="' . $row->id . '"
                                data-hosting="' . $row->cloud->package_name . '"
                                data-toggle="modal"
                                data-target="#transferModal"
                                title="Chuyển dữ liệu">
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

    public function getStatusService($id)
    {
        $service = Service::find($id);
        // dd($service);
        $status = $service->status;
        // Trả về dữ liệu dưới dạng JSON
        return response()->json(['status' => $status]);
    }

    public function getGiaHanService($id)
    {

        $service = Service::find($id);
        $activeAt = Carbon::parse($service->active_at);
        $expirationDate = (clone $activeAt)->addMonths($service->number);

        return response()->json([
            'activeAt' => $activeAt->format('Y-m-d'),
            'expirationDate' => $expirationDate->format('Y-m-d')
        ]);
    }

    public function updateStatus(Request $request)
    {
        $service = Service::findOrFail($request->id);

        $service->status = $request->status;
        $service->save();

        return response()->json(['message' => 'Cập nhật thành công']);
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


    public function giaHan(Request $request)
    {

        $id = $request->input('service_id');
        $extend_time = $request->input('extend_time');

        $service = Service::find($id);
        if ($service) {
            $service->number = $service->number + $extend_time;
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
        // dd($serviceRequest->toArray());

        $service = new Service();
        $service->email = $serviceRequest->email;
        $service->active_at = $serviceRequest->active_at;
        $service->number = $serviceRequest->end_date;
        $service->status = 'active';
        $service->type = $type;
        $service->price = 0;
        $service->product_id = $serviceRequest->package_name;
        if ($type == 'domain') {
            // $service->product_id = $serviceRequest->package_name;

            $domain = $serviceRequest->domain;
            $ten_mien = pathinfo($domain, PATHINFO_FILENAME);
            $duoi_mien = '.' . pathinfo($domain, PATHINFO_EXTENSION);
            $service->domain = $ten_mien;
            $service->domain_extension = $duoi_mien;
        }
        if ($type == 'email' || $type == 'hosting') {
            $service->domain = $serviceRequest->domain;
        }
        if ($type == 'cloud') {
            $service->os_id = $serviceRequest->os_id;
        }
        $service->save();

        return redirect()->back()->with('success', 'Thêm mới dịch vụ thành công!');
    }

    public function listhotel(Request $request, $date = null)
    {
        $title = "Quản lý dịch vụ Khách sạn";
        if ($request->ajax()) {
            $data = Service::where('service.type', 'hotel')
            ->join('users', function ($join) {
                $join->on(DB::raw("CONVERT(users.email USING utf8mb4) COLLATE utf8mb4_unicode_ci"), '=', DB::raw("CONVERT(service.email USING utf8mb4) COLLATE utf8mb4_unicode_ci"));
            })
            ->leftJoin('provinces', 'users.province', '=', 'provinces.id') // Thêm dòng này
            ->select('service.*', 'users.full_name', 'users.phone_number', 'provinces.name as province_name')
            ->orderBy('service.created_at', 'desc');

            if ($date == 'expire_soon') {
                $data->whereRaw('DATEDIFF(DATE_ADD(active_at, INTERVAL number MONTH), NOW()) BETWEEN 1 AND 30');
            }
            if ($date == 'expire') {
                $data->whereRaw('DATEDIFF(DATE_ADD(active_at, INTERVAL number MONTH), NOW()) < 0');
            }
            return DataTables::of($data)
                ->filterColumn('user_info', function ($query, $keyword) {
                    $query->where(function ($q) use ($keyword) {
                        $q->where('users.full_name', 'like', "%$keyword%")
                            ->orWhere('users.phone_number', 'like', "%$keyword%");
                    });
                })
                ->filterColumn('provinces', function ($query, $keyword) {
                    $query->where('provinces.name', 'like', "%$keyword%");
                })
                ->addIndexColumn()
                ->addColumn('user_info', function ($row) {
                    $user = User::where('email', $row->email)->first();
                    return $user ? $user->full_name . '<br>(' . $user->phone_number . ')' : '';
                })
                ->addColumn('provinces', function ($row) {
                    $user = User::where('email', $row->email)->first();
                    return $user ? $user->province1?->name : '' ;
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
                        return $expirationDate->format('d-m-Y') . '<p class="endday">(  - ' . $daysOverdue . ' ngày )</p>';
                    }

                    $daysLeft = $expirationDate->diffInDays(Carbon::now());
                    if ($daysLeft < 30) {
                        return $expirationDate->format('d-m-Y') . '<p class="endday">(  ' . $daysLeft . ' ngày )</p>';
                    }

                    return $expirationDate->format('d-m-Y');
                })->rawColumns(['enddate'])
                ->editColumn('active', function ($row) {
                    return ' <div class="toggle-container justify-content-center">
                    <label class="switch">
                        <input type="checkbox" class="toggleStatus" data-id="' . $row->id . '"' . ($row->status == 'active' ? ' checked' : '') . '>
                        <span class="slider"></span>
                    </label>
                </div>';
                })->rawColumns(['active'])
                ->editColumn('giahan', function ($row) {
                    return '<a href="' . route('order.show', $row->id) . '" class="btn btn-primary btn-sm edit"> Gia hạn </a>';
                })->rawColumns(['giahan'])
                ->addColumn('action', function ($row) {
                    return '
                    <div class="d-flex">
                    <div class="dropdown">
                        <!-- Icon hiển thị modal -->
                        <span style="font-size:26px; cursor:pointer; margin-right:15px" class="action" onclick="toggleMenu(\'' . $row->id . '\')">
                            <i class="fas fa-cog"></i>
                        </span>

                        <!-- Menu Dropdown -->
                        <div id="menu-' . $row->id . '" class="dropdown-menu">
                            <ul>
                                <li><a href="#" onclick="openModal(' . $row->id . ')">Nội dung</a></li>

                                 <li><a href="#" onclick="openModalGiaHan(' . $row->id . ')">Gia hạn</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
                    ';
                })->rawColumns(['action', 'giahan', 'enddate', 'active', 'user_info', 'provinces'])
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
