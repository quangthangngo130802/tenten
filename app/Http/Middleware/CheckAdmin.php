<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckAdmin
{
    public function handle(Request $request, Closure $next)
    {
        // Kiểm tra xem người dùng đã đăng nhập chưa và role_id
        if (Auth::check() && Auth::user()->role_id === 1) {
            return $next($request);
        }

        return redirect()->route('dashboard');  // Giả sử customer có route 'customer.index'
    }
}
