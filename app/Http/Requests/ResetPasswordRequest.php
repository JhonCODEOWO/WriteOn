<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Password;

class ResetPasswordRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        //Authorize if the token is valid
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
            "token" => "required|string",
            "email" => "required|email|exists:users,email",
            "password" => "required|confirmed",
            "password_confirmation" => "required"
        ];
    }

    protected function prepareForValidation()
    {
        $this->merge([
            "email" => $this->route('email')
        ]);
    }
}
