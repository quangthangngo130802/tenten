<?php

namespace App\Http\Controllers;

use App\Models\Order;
use com_exception;
use Faker\Provider\ar_EG\Company;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    //
    public function dashboard(){
        $page = 'Dashboard';
        $title = 'Dashboard';
        $user = Auth::user();
        if($user->role_id == 1){
            $nopayment = Order::where('status', 'nopayment')->get();
        }else {
            $nopayment = Order::where('status', 'nopayment')->where('email', $user->email)->get();
        }

        return view('backend.dashboard', compact('page', 'title','nopayment'));
    }
}
