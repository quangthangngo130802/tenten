<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CompanyRequest extends FormRequest
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
            'company_name'         => 'required|string|max:255',
            'company_address'      => 'required|string|max:255',
            'company_phone'        => 'required|numeric|regex:/^0[0-9]{9,10}$/', // Kiểm tra số điện thoại
            'company_email'        => 'required|email|unique:company_config,company_email',
            'company_website'      => 'nullable|url',
            'tax_id'               => 'required|string|max:20',
            'vat_rate'             => 'required|numeric|min:0|max:100',
            'representative_name'  => 'required|string|max:255',
            'representative_position' => 'nullable|string|max:255',
            'representative_phone' => 'nullable|numeric|regex:/^0[0-9]{9,10}$/',
            'representative_email' => 'nullable|email',
        ];
    }

    /**
     * Lấy các thông báo lỗi tuỳ chỉnh cho các quy tắc xác thực.
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
            'company_name'         => 'Tên công ty',
            'company_address'      => 'Địa chỉ công ty',
            'company_phone'        => 'Số điện thoại công ty',
            'company_email'        => 'Email công ty',
            'company_website'      => 'Website công ty',
            'tax_id'               => 'Mã số thuế',
            'vat_rate'             => 'Tỷ lệ VAT',
            'representative_name'  => 'Tên đại diện',
            'representative_position' => 'Chức vụ đại diện',
            'representative_phone' => 'Số điện thoại đại diện',
            'representative_email' => 'Email đại diện',
        ];
    }
}
