<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserRequest extends FormRequest
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
        return [
            'username' => 'required|string|unique:users,username,' . $this->id,
            'password' => $this->isMethod('post') ? 'required|string|min:6' : 'nullable|string|min:6',
            'full_name' => 'required|string|max:255',
            'gender' => 'required|in:male,female,other',
            'birth_date' => 'required|date',
            'identity_number' => 'nullable|string|max:20|unique:users,identity_number,' . $this->id,
            'tax_code' => 'nullable|string|max:20|unique:users,tax_code,' . $this->id,
            'country' => 'nullable|string|max:255',
            'province' => 'nullable|string|max:255',
            'district' => 'nullable|string|max:255',
            'ward' => 'nullable|string|max:255',
            'address' => 'nullable|string|max:500',
            'phone_number' => 'required|string|max:15|unique:users,phone_number,' . $this->id,
            'email' => 'required|string|email|max:255|unique:users,email,' . $this->id,
            'role_id' => 'required|integer',
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
            'role_id' => 'Vai trò',
        ];
    }
}
