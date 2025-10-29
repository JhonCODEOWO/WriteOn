<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateUserRequest extends FormRequest
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
            "name" => "required|string|regex:/^\p{Lu}\p{L}*\s((\p{Lu}\p{L}*)+\s)*\p{Lu}\p{L}*$/u",
            "email" => "required|unique:users,email",
            "password" => "required|string|min:7|confirmed|regex:/^((?=\S*?[A-Z])(?=\S*?[a-z])(?=\S*?[0-9]).{6,})\S$/",
            "password_confirmation" => "required|string|min:7|regex:/^((?=\S*?[A-Z])(?=\S*?[a-z])(?=\S*?[0-9]).{6,})\S$/",
        ];
    }

    public function messages()
    {
        return [
            "name.regex" => "You must insert a full name with at least 2 words each of them should start with a uppercase character at the start and the rest of it with lowercase characters without special characters and only one blank space for each word.",
            "password.regex" => "The password must be of 7 characters and include at least one uppercase letter, one lowercase letter, and one number with no spaces.",
            "password_confirmation.regex" => "The password must be of 7 characters and include at least one uppercase letter, one lowercase letter, and one number with no spaces."
        ];
    }
}
