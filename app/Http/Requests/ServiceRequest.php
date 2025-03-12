<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Log;

class ServiceRequest extends FormRequest
{
   /**
     * Xác định người dùng có quyền thực hiện yêu cầu này hay không.
     *
     * @return bool
     */
    public function authorize()
    {
        return true; // Đảm bảo yêu cầu có thể được thực thi
    }

    /**
     * Lấy các quy tắc xác thực cho yêu cầu.
     *
     * @return array
     */
    public function rules()
    {
        $rules = [
            'email'              => 'required',
            'package_name'       => 'required',
            'active_at'          => 'required',
            'end_date'           => 'required',
        ];
        Log::info($this->route('type'));

        // Điều kiện cho domain khi type là hosting hoặc email
        if ($this->route('type') == 'hosting' || $this->route('type') == 'email') {
            $rules['domain'] = 'required|string|max:255';
        }

        // Điều kiện cho backup khi type là cloud
        if ($this->route('type') == 'cloud') {
            $rules['backup'] = 'required|in:0,1';
            $rules['os_id'] = 'required';
        }

        return $rules;
    }

    /**
     * Lấy các thông báo lỗi tuỳ chỉnh cho các quy tắc xác thực.
     *
     * @return array
     */
    public function messages()
    {
        return __('request.messages');
    }

    /**
     * Lấy các thuộc tính tùy chỉnh cho các trường trong yêu cầu.
     *
     * @return array
     */
    public function attributes()
    {
        return [
            'email'         => 'Người dùng',
            'package_name'  => 'Tên gói',
            'os_id'         => 'Hệ điều hành',
            'domain'        => 'Domain',
            'backup'        => 'Sao lưu',
            'active_at'     => 'Ngày bắt đầu',
            'end_date'      => 'Thời hạn',
        ];
    }
}
