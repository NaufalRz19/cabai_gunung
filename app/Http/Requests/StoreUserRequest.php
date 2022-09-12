<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreUserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'name' => ['required', 'string', 'max:100'],
            'email' => ['required', 'unique:users,email', 'string', 'max:100'],
            'password' => ['required', 'string', 'min:8', 'max:100', 'confirmed'],
            'address' => ['nullable', 'string', 'max:255'],
            'phone_number' => ['nullable', 'string', 'max:15'],
            'status' => ['required', 'string', 'in:admin,retail,petani,kurir'],
        ];
    }
}
