<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Service;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Yajra\DataTables\Facades\DataTables;

class ApiTokenController extends Controller
{
    public function index(Request $request,)
    {
        $title = "Quản lý dịch vụ Khách sạn";
        if ($request->ajax()) {
            $data = Service::where('service.type', 'hotel')
                ->join('users', function ($join) {
                    $join->on(DB::raw("CONVERT(users.email USING utf8mb4) COLLATE utf8mb4_unicode_ci"), '=', DB::raw("CONVERT(service.email USING utf8mb4) COLLATE utf8mb4_unicode_ci"));
                })
                ->leftJoin('provinces', 'users.province', '=', 'provinces.id')
                ->select('service.*', 'users.full_name', 'users.phone_number', 'provinces.name as province_name')
                ->orderBy('service.created_at', 'desc');

            return DataTables::of($data)
                ->filterColumn('user_info', function ($query, $keyword) {
                    $query->where(function ($q) use ($keyword) {
                        $q->where('users.full_name', 'like', "%$keyword%")
                            ->orWhere('users.phone_number', 'like', "%$keyword%");
                    });
                })
                ->filterColumn('link', function ($query, $keyword) {
                    $query->whereRaw("CONCAT(domain, domain_extension) LIKE ?", ["%{$keyword}%"]);
                })

                ->addIndexColumn()
                ->addColumn('user_info', function ($row) {
                    $user = User::where('email', $row->email)->first();
                    return $user && !empty($user->full_name) && !empty($user->phone_number)
                        ? $user->full_name . '<br>(' . $user->phone_number . ')'
                        : ($user && !empty($user->full_name) ? $user->full_name : '');
                })
                ->addColumn('provinces', function ($row) {
                    $user = User::where('email', $row->email)->first();
                    return $user && $user->province1 && !empty($user->province1->name)
                        ? $user->province1->name
                        : '';
                })

                ->addColumn('link', function ($row) {
                    $subdomain = $row->domain; // ví dụ: 'hungtran'
                    $link = "http://{$subdomain}.fasthotels.vn/api/login-by-subdomain/{$subdomain}";

                    return '<a href="' . $link . '" target="_blank"
                             style="color:#007bff; text-decoration:underline">'
                        . e($subdomain . '.fasthotel.vn') .
                        '</a>';
                })
                ->editColumn('token', function ($row) {
                    $shortToken = substr($row->token, 0, 20) . '...';

                    return '<span title="' . e($row->token) . '">' . e($shortToken) . '</span>';
                })

                ->addColumn('action', function ($row) {
                    return '<div>
                        <button class="btn btn-sm btn-outline-primary" style="padding: 6px 15px" onclick="regenerateToken(' . $row->id . ')">Làm mới</button>
                    </div>';
                })->rawColumns(['action', 'user_info',  'link', 'token'])
                ->make(true);
        }
        $page = 'Quản lý dịch vụ khách sạn';
        return view('backend.apitoken.index', compact('title', 'page'));
    }

    public function regenerateToken($id)
    {
        $service = Service::find($id);

        if (!$service) {
            return response()->json([
                'success' => false,
                'message' => 'Không tìm thấy dịch vụ!'
            ], 404);
        }

        // Tạo token mới
        $newToken = Str::random(64);

        // Cập nhật token
        $service->token = $newToken;
        $service->save();

        return response()->json([
            'success' => true,
            'message' => 'Token đã được làm mới!',
            'token' => $newToken,
        ]);
    }
}
