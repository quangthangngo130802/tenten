<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class ProfileRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        $userId = Auth::user()->id; // Hoặc $user = Auth::user()->id;

        return [
            'username' => 'nullable|string|unique:users,username,' . $userId,
            'password' => 'nullable|string|min:6',
            'full_name' => 'required|string|max:255',
            'gender' => 'required|in:male,female,other',
            'birth_date' => 'required|date',
            'identity_number' => 'required|string|max:20|unique:users,identity_number,' . $userId,
            'tax_code' => 'required|string|max:20|unique:users,tax_code,' . $userId,
            // 'country' => 'required|string|max:255',
            'province' => 'required|string|max:255',
            'district' => 'required|string|max:255',
            'ward' => 'required|string|max:255',
            'address' => 'required|string|max:500',
            'phone_number' => 'required|string|max:15',
            'email' => 'nullable|string|email|max:255|unique:users,email,' . $userId,
        ];
    }

    /**
     * Get the custom messages for validator errors.
     */
    public function messages(): array
    {
        return __('request.messages');
    }

    public function attributes(): array
    {
        return [
            'username' => 'Tên đăng nhập',
            'password' => 'Mật khẩu',
            'full_name' => 'Họ và tên',
            'gender' => 'Giới tính',
            'birth_date' => 'Ngày sinh',
            'identity_number' => 'CMND/CCCD/Hộ chiếu',
            'tax_code' => 'Mã số thuế',
            'country' => 'Quốc gia',
            'province' => 'Tỉnh/Thành phố',
            'district' => 'Quận/Huyện',
            'ward' => 'Xã/Phường',
            'address' => 'Địa chỉ',
            'phone_number' => 'Số điện thoại',
            'email' => 'Email',
        ];
    }
}
