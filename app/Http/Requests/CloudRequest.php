<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CloudRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Các quy tắc validation áp dụng cho yêu cầu.
     */
    public function rules(): array
    {
        return [
            'package_name'  => 'required|string|max:255',
            'cpu'           => 'required|string|max:50',
            'ram'           => 'required|string|max:50',
            'ssd'           => 'required|string|max:50',
            'network'       => 'required|string|max:50',
            'price'         => 'required|numeric|min:0',
            'total_cost'    => 'required|numeric|min:0',
            'type_id' => 'required|integer',
        ];
    }

    /**
     * Tùy chỉnh thông báo lỗi.
     */
    public function messages(): array
    {
        return __('request.messages');
    }
    public function attributes(): array
    {
        return [
            'package_name'        => 'Tên gói Cloud',
            'cpu'       => 'CPU',
            'ram'         => 'Dung lượng RAM',
            'ssd'     => 'SSD',
            'network'   => 'Mạng',
            'price' => 'Giá theo tháng',
            'total_cost'  => 'Giá theo năm',

            'type_id'     => 'Loại Cloud',
        ];
    }
}
