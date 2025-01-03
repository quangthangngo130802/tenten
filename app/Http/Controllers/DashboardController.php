<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderDetail;
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
            $orderactive = OrderDetail::where('status', 'active')->count();
            $serviceRenewCount = OrderDetail::where('status', 'active')
            ->where(function ($query) {
                $query->whereRaw('DATEDIFF(DATE_ADD(active_at, INTERVAL number MONTH), NOW()) <= 30');
            })
            ->count();
        } else {
            $nopayment = Order::where('status', 'nopayment')->where('email', $user->email)->get();
            $orderactive = OrderDetail::where('status', 'active')
            ->whereHas('order', function ($query) use ($user) {
                $query->where('email', $user->email);
            })
            ->count();

            $serviceRenewCount = OrderDetail::where('status', 'active')
            ->whereHas('order', function ($query) use ($user) {
                $query->where('email', $user->email);
            })
            ->where(function ($query) {
                $query->whereRaw('DATEDIFF(DATE_ADD(active_at, INTERVAL number MONTH), NOW()) <= 30');
            })
            ->count();
        }

        return view('backend.dashboard', compact('page', 'title', 'nopayment','orderactive', 'serviceRenewCount' ));
    }
}
