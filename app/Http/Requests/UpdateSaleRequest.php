<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateSaleRequest extends FormRequest
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
            'user_id' => ['nullable', 'exists:users,id'],
            'sales_number' => ['nullable', 'string', 'max:20'],
            'chilli_id.*' => ['nullable', 'exists:chillis,id'],
            'selling_price.*' => ['nullable', 'integer'],
            'total.*' => ['nullable', 'numeric'],
            'image_url.*' => ['nullable', 'max:5120', 'mimes:png,jpg,jpeg,PNG,JPG,JPEG'],
            'is_success' => ['required', 'in:0,1'],
        ];
    }
}
