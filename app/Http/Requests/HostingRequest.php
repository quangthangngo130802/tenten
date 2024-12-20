<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class HostingRequest extends FormRequest
{
    /**
     * Xác định xem người dùng có quyền gửi yêu cầu này hay không.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true; // Thay đổi nếu cần, chỉ định quyền truy cập
    }

    /**
     * Lấy các quy tắc xác thực cho yêu cầu.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'package_name' => 'required|string|max:255',
            'storage' => 'required|string|max:50',
            'bandwidth' => 'required|string|max:50',
            'website_limit' => 'required|integer|min:1',
            'ssl_included' => 'required|boolean',
            'price' => 'required|numeric|min:0',
            'tech_support' => 'required|string|max:255',
            'backup_frequency' => 'required|string|max:255',
        ];
    }

    /**
     * Lấy các thông điệp tùy chỉnh cho các lỗi xác thực.
     *
     * @return array
     */
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
            'package_name'      => 'Tên gói',
            'storage'           => 'Dung lượng',
            'bandwidth'         => 'Băng thông',
            'website_limit'     => 'Giới hạn website',
            'ssl_included'      => 'Tích hợp SSL',
            'price'             => 'Đơn giá',
            'tech_support'      => 'Hỗ trợ kỹ thuật',
            'backup_frequency'  => 'Tần suất backup',
        ];
    }
}
