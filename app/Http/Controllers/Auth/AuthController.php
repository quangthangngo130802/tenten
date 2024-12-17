<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\Auth\LoginUserRequest;

class AuthController extends Controller
{

    public function login()
    {
        return view('auth.login');
    }

    public function authenticate(LoginUserRequest $request)
    {

        $credentials = $request->only(['email', 'password']);

        $remember = $request->boolean('remember');

        if (auth()->attempt($credentials, $remember)) {

            toastr()->success('Đăng nhập thành công.');

            return redirect()->route('dashboard');
        } else {
            toastr()->error('Tài khoản hoặc mật khẩu không chính xác!');
            return back();
        }
    }

    public function logout()
    {

        auth()->logout();

        toastr()->success('Đăng xuất thành công.');

        return redirect()->route('login');
    }
}
