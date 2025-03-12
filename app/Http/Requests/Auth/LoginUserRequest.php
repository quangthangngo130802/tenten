<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

class LoginUserRequest extends FormRequest
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
    public function rules(): array
    {
        return [
            'username' => [
                'required',
                function ($attribute, $value, $fail) {
                    $exists = \App\Models\User::where('username', $value)
                        ->orWhere('email', $value)
                        ->exists();
                    if (!$exists) {
                        $fail('Tài khoản không tồn tại.');
                    }
                }
            ],
            'password'       => 'required|min:6|max:20',
            'g-recaptcha-response' => 'required'
        ];
    }


    public function attributes()
    {
        return [
            'username' => 'Username',
            'password' => 'Mật khẩu',
            'g-recaptcha-response' => 'reCaptcha',
        ];
    }

    public function messages()
    {
        return __('request.messages');
    }

    // public function failedValidation($validator)
    // {

    //     throw new HttpResponseException(response()->json([
    //         'status' => false,
    //         'errors' => $validator->errors()
    //     ]));
    // }
}
