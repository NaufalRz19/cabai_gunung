<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreSaleRequest extends FormRequest
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
            'user_id' => ['required', 'exists:users,id'],
            // 'sales_number' => ['required', 'string', 'max:20'],
            'chilli_id.*' => ['required', 'exists:chillis,id'],
            'lat' => ['required'],
            'long' => ['required'],
            'selling_price.*' => ['required', 'integer'],
            'total.*' => ['required', 'numeric'],
            // 'image_url.*' => ['nullable', 'max:5120', 'mimes:png,jpg,jpeg,PNG,JPG,JPEG'],
            'is_success' => ['required', 'in:0,1'],
        ];
    }
}