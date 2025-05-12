<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Response;

class CheckAdmin
{
    public function handle(Request $request, Closure $next)
    {
        // Kiểm tra xem người dùng đã đăng nhập chưa và role_id
        $user = Auth::user();

        // Nếu không đăng nhập, hoặc không phải role 1 hoặc 3 → redirect
        if (!$user || !in_array($user->role_id, [1, 3])) {
            return redirect()->route('dashboard');
        }

        // Nếu role 3 → chỉ cho phép route name chứa "hotel"
        if ($user->role_id === 3) {
            // Lấy route name hiện tại (vd: service.hotel.list.hotel)
            $routeName = optional($request->route())->getName();

            // Nếu không phải route liên quan đến hotel → chặn
            if (!Str::contains($routeName, 'hotel')) {
                return redirect()->route('dashboard')->with('error', 'Bạn không có quyền truy cập.');
            }
        }

        return $next($request);
    }
}
