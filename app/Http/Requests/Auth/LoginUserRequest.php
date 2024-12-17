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
            'email'          => 'required|email|exists:users,email',
            'password'       => 'required|min:6|max:20',
            'g-recaptcha-response' => 'required'
        ];
    }


    public function attributes()
    {
        return [
            'email' => 'Email',
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
