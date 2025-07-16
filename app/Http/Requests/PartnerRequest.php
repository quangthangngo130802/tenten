<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PartnerRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules()
    {
        return [
            'full_name'     => 'required|string|max:100',
            'company'       => 'nullable|string',
            'company_phone' => 'nullable|string|max:20',
            'industry'      => 'nullable|string|max:100',
            'position'      => 'nullable|string|max:100',
            'email'         => 'required|email|max:100',
            'tax_code'      => 'nullable|string|max:50',
            'source'        => 'nullable|string|max:100',
            'phone'         => 'required|string|max:100',
            'address'       => 'nullable|string|max:100',
            'note'          => 'nullable|string',
            'status'         => 'nullable|string',
        ];
    }

    public function messages(): array
    {
        return __('request.messages');
    }
    public function attributes()
    {
        return [
            'full_name'     => 'Họ tên',
            'company'       => 'Công ty',
            'company_phone' => 'Số điện thoại công ty',
            'industry'      => 'Ngành nghề',
            'position'      => 'Chức vụ',
            'email'         => 'Email',
            'tax_code'      => 'Mã số thuế',
            'source'        => 'Nguồn',
            'note'          => 'Ghi chú',
        ];
    }
}
