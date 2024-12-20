<?php

namespace App\Http\Controllers\Auth;

use App\Http\Requests\ResetPasswordRequest;
use App\Mail\AccountEmail;
use App\Models\District;
use App\Models\Province;
use App\Models\User;
use App\Models\Ward;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\Auth\LoginUserRequest;
use App\Http\Requests\RegisterRequest;
use App\Http\Requests\UserRequest;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;


class AuthController extends Controller
{

    public function login()
    {
        return view('auth.login');
    }

    public function authenticate(LoginUserRequest $request)
    {

        $credentials = $request->only(['username', 'password']);
        $remember = $request->boolean('remember');


        $user = User::where('username', $credentials['username'])->first();
        // Kiểm tra nếu tài khoản không được kích hoạt
        if ($user->status !=='active') {
            toastr()->error('Tài khoản của bạn chưa được kích hoạt. Vui lòng liên hệ quản trị viên.');
            return back();
        }

        // Thực hiện đăng nhập nếu thông tin hợp lệ
        if (auth()->attempt($credentials, $remember)) {
            toastr()->success('Đăng nhập thành công.');
            return redirect()->route('dashboard');
        } else {
            toastr()->error('Tài khoản hoặc mật khẩu không chính xác!');
            return back();
        }
    }

    public function logout()
    {

        auth()->logout();

        toastr()->success('Đăng xuất thành công.');

        return redirect()->route('login');
    }

    public function register()
    {
        $province = Province::get();

        return view('auth.register', compact('province'));
    }

    public function submitregister(RegisterRequest $request)
    {
        $credentials = $request->validated();
        $credentials = Arr::except($credentials, ['g-recaptcha-response']);
        $credentials['password'] = bcrypt($credentials['password']);
        $credentials['token'] = Str::random(60);
        User::create($credentials);
        toastr()->success('Đăng ký thành công.');
        return redirect()->route('login')->with('success', 'Đăng ký thành công!');
    }

    public function getDistricts(Request $request)
    {
        $province_id = $request->input('province_id');
        $districts = District::where('province_id', $province_id)->get();
        return response()->json(['districts' => $districts]);
    }

    // Lấy danh sách xã theo quận huyện
    public function getWards(Request $request)
    {
        $district_id = $request->input('district_id');
        $wards = Ward::where('district_id', $district_id)->get();
        return response()->json(['wards' => $wards]);
    }

    public function resetpass()
    {
        $province = Province::get();
        return view('auth.resetpass');
    }


    public function sendResetPassword(ResetPasswordRequest $request)
    {
        $email = $request->input('email');

        $password = Str::random(6);

        $user = User::where('email', $email)->first();

        $user->update([
            'password' => bcrypt($password),
            'token' => Str::random(60),
        ]);

        $data = [
            'email' => $email,
            'password' => $password,
            'username' => $user->username,
            'reset_link' => 'test'
        ];

        Mail::to($email)->send(new AccountEmail($data));

        return redirect()->back()->with('success', 'Email đã được gửi thành công!');
    }
}

