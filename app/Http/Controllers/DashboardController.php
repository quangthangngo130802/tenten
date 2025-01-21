<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Service;
use com_exception;
use Faker\Provider\ar_EG\Company;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    //
    public function dashboard()
    {
        $page = 'Dashboard';
        $title = 'Dashboard';
        $user = Auth::user();
        if ($user->role_id == 1) {
            $nopayment = Order::where('status', 'nopayment')->get();
            $orderactive = Service::where('status', 'active')
                // ->whereHas('order', function ($query) use ($user) {
                //     $query->where('order_type', 1);
                // })
                ->count();
            $serviceRenewCount = Service::where('status', 'active')
                // ->whereHas('order', function ($query) use ($user) {
                //     $query->where('order_type', 1);
                // })
                ->where(function ($query) {
                    $query->whereRaw('DATEDIFF(DATE_ADD(active_at, INTERVAL number MONTH), NOW()) <= 30');
                })
                ->count();
        } else {
            $nopayment = Order::where('status', 'nopayment')->where('email', $user->email)->get();
            $orderactive = Service::where('status', 'active')
                // ->whereHas('order', function ($query) use ($user) {
                //     $query->where('email', $user->email)->where('order_type', 1);
                // })
                ->count();

            $serviceRenewCount = Service::where('status', 'active')
                // ->whereHas('order', function ($query) use ($user) {
                //     $query->where('email', $user->email)->where('order_type', 1);
                // })
                ->where(function ($query) {
                    $query->whereRaw('DATEDIFF(DATE_ADD(active_at, INTERVAL number MONTH), NOW()) <= 30');
                })
                ->count();
        }

        return view('backend.dashboard', compact('page', 'title', 'nopayment', 'orderactive', 'serviceRenewCount'));
    }

    public function userService(Request $request)
    {
        $page = "Dịch vụ đang sử dụng";
        $title = "Dịch vụ đang sử dụng";
        $user = Auth::user();
        $types = $this->getNearExpirationOrders($user);
        // dd($types);

        return view('userservice.index', compact('page', 'title', 'types'));
    }

    function getNearExpirationOrders($user)
    {
        $result = Service::query()
            ->where('status', 'active')
            // ->whereNull('orderdetail_id')
            // ->when($user->role_id != 1, function ($query) use ($user) {
            //     $query->whereHas('order', function ($subQuery) use ($user) {
            //         $subQuery->where('email', 'like', $user->email);
            //     });
            // })
            ->selectRaw("
                    type,
                        COUNT(*) as active_count,  -- Đếm tất cả bản ghi có status = 'active'
                        SUM(CASE
                            WHEN DATEDIFF(DATE_ADD(active_at, INTERVAL number MONTH), NOW()) BETWEEN 1 AND 30 THEN 1
                            ELSE 0
                        END) as expiring_soon_count,
                        SUM(CASE
                            WHEN DATEDIFF(DATE_ADD(active_at, INTERVAL number MONTH), NOW()) <= 0 THEN 1
                            ELSE 0
                        END) as expired_count
                    ")
            // ->whereIn('type', ['hosting', 'cloud'])
            ->groupBy('type')
            ->get()
            ->keyBy('type');

        // Định dạng kết quả thành mảng mong muốn
        $formattedResult = [
            'hosting' => [
                'active_count' => $result->where('type', 'hosting')->first()->active_count ?? 0,
                'expiring_soon_count' => $result->where('type', 'hosting')->first()->expiring_soon_count ?? 0,
                'expired_count' => $result->where('type', 'hosting')->first()->expired_count ?? 0,
            ],
            'cloud' => [
                'active_count' => $result->where('type', 'cloud')->first()->active_count ?? 0,
                'expiring_soon_count' => $result->where('type', 'cloud')->first()->expiring_soon_count ?? 0,
                'expired_count' => $result->where('type', 'cloud')->first()->expired_count ?? 0,
            ],
            'email' => [
                'active_count' => $result->where('type', 'email')->first()->active_count ?? 0,
                'expiring_soon_count' => $result->where('type', 'email')->first()->expiring_soon_count ?? 0,
                'expired_count' => $result->where('type', 'email')->first()->expired_count ?? 0,
            ]
        ];

        return $formattedResult;
    }


}
