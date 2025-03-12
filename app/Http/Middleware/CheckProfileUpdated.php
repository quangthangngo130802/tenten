<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class CheckProfileUpdated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();
        // dd($user);

        if ( $user->identity_number == null) {

            return redirect()->route('profile')->with('error', 'Bạn cần cập nhật thông tin trước khi sử dụng hệ thống.');
        }

        return $next($request);
    }
}
