<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class EmailRequest extends FormRequest
{
    /**
     * Xác định người dùng có quyền thực hiện yêu cầu này hay không.
     *
     * @return bool
     */
    public function authorize()
    {
        return true; // Đảm bảo rằng yêu cầu này có thể được thực thi
    }

    /**
     * Lấy các quy tắc xác thực cho yêu cầu.
     *
     * @return array
     */
    public function rules()
    {
        return [
            // 'email' => ['required', 'email', Rule::unique('emails')->where(function ($query) {
            //     // Kiểm tra trùng với package_name trong bảng emails
            //     return $query->where('package_name', $this->package_name);
            // })],
            // 'email_type' => 'required|int', // Điều kiện cho package_name
            'package_name' => 'required|string|max:255', // Điều kiện cho package_name
            'storage' => 'required|integer',
            'domain_alias' => 'required|string',
            'webmail' => 'required|string',
            'sender_hour' => 'required|string',
            'backup' => 'required|string',
            'price' => 'required|numeric',
            'setting' => 'required|string',
        ];
    }

    public function messages(): array
    {
        return __('request.messages');
    }

    /**
     * Lấy các thuộc tính tùy chỉnh cho các trường trong yêu cầu.
     *
     * @return array
     */
    public function attributes(): array
    {
        return [
            'email_type' => 'Loại Email',
            'package_name'      => 'Tên gói Email',
            'storage'           => 'Dung lượng trên 01 User',
            'domain_alias'      => 'Địa chỉ email',
            'webmail'        => 'Số lượng email gửi đi/ngày',
            'sender_hour'      => 'Số lượng email gửi đi/tháng	',
            'backup'      => 'Tổng dung lượng file đính kèm/tháng (GB)',
            'price'             => 'Giá theo tháng',
            'setting'             => 'Giá theo tháng',
        ];
    }
}
