<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BusinessRequest extends FormRequest
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
            'businessCode' => 'required|',
            'businessName' => 'required|',
            'businessAddress' => 'required|',
            'representative' => 'required|',
            'contactPhone' => 'nullable|',
            'contactEmail' => 'required|',
        ];
    }

    /**
     * Get custom error messages for validation rules.
     */
    public function messages(): array
    {
        return __('request.messages');
    }

    /**
     * Get custom attributes for validator errors.
     */
    public function attributes(): array
    {
        return [
            'businessCode' => 'Mã số doanh nghiệp',
            'businessName' => 'Tên doanh nghiệp',
            'businessAddress' => 'Địa chỉ doanh nghiệp',
            'representative' => 'Tên người đại diện',
            'contactPhone' => 'Số điện thoại',
            'contactEmail' => 'Email liên hệ',
        ];
    }
}
