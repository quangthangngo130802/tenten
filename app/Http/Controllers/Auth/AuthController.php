<?php

namespace App\Http\Controllers\Auth;

use App\Http\Requests\ResetPasswordRequest;
use App\Mail\AccountEmail;
use App\Models\District;
use App\Models\Province;
use App\Models\User;
use App\Models\Ward;
use Carbon\Carbon;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\Auth\LoginUserRequest;
use App\Http\Requests\RegisterRequest;
use App\Http\Requests\UserRequest;
use App\Mail\AccountActivation;
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
        if ($user->status !== 'active') {
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
        $credentials = Arr::except($credentials, ['g-recaptcha-response']); // Xóa reCAPTCHA khỏi dữ liệu

        $credentials['password'] = bcrypt($credentials['password']);
        $credentials['token'] = Str::random(60); // Tạo token kích hoạt
        $credentials['token_expires_at'] = Carbon::now()->addMinutes(15);
        $user = User::create($credentials);

        $user->save();

        $activationUrl = route('activate.account', ['token' => $user->token]);

        $data = [
            'name' => $credentials['full_name'],
            'username' => $credentials['username'],
            'activationUrl' => $activationUrl,
        ];

        Mail::to($credentials['email'])->send(new AccountActivation($data));

        // Thông báo thành công
        // toastr()->success('Đăng ký thành công. Vui lòng kiểm tra email để kích hoạt tài khoản.');
        return redirect()->route('login')->with('success', 'Đăng ký thành công! Vui lòng kiểm tra email để kích hoạt tài khoản.');
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

    public function activateAccount($token)
    {
        $user = User::where('token', $token)->first();

        if (!$user) {
            return redirect()->route('login')->with('error', 'Liên kết kích hoạt không hợp lệ.');
        }

        if (Carbon::now()->greaterThan($user->token_expires_at)) {
            return redirect()->route('login')->with('error', 'Liên kết kích hoạt đã hết hạn. Vui lòng yêu cầu kích hoạt lại.');
        }

        if ($user->status === 'active') {
            return redirect()->route('login')->with('error', 'Tài khoản của bạn đã được kích hoạt rồi.');
        }

        $user->status = 'active';
        $user->save();

        return redirect()->route('login')->with('success', 'Tài khoản của bạn đã được kích hoạt thành công!');
    }
}
