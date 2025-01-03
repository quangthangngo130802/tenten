<?php

namespace App\Http\Controllers;

use App\Http\Requests\ClientRequest;
use App\Http\Requests\ProfileRequest;
use App\Http\Requests\UserRequest;
use App\Models\Province;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    public function profile(){
        $page = 'Thông tin tài khoản';
        $title = 'Thông tin tài khoản';
        $user = Auth::user();
        $province = Province::get();
        return view('profile', compact('user', 'title', 'page', 'province'));
    }

    public function updateprofile(ProfileRequest $request)
    {
        $user = Auth::user();
         /**
         * @var User $user
         */
        $credentials = $request->validated();
        if (!empty($credentials['password'])) {
            $credentials['password'] = bcrypt($credentials['password']);
        } else {
            unset($credentials['password']);
        }

        $user->update($credentials);
        toastr()->success('Cập nhật thành công.');
        return redirect()->back();
    }
}
