<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePurchaseRequest extends FormRequest
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
            // 'purchase_number' => ['required', 'string', 'max:20'],
            'payment_method' => ['required', 'in:cod,transfer'],
            'chilli_price_id.*' => ['required', 'exists:chilli_prices,id'],
            'healthy_amount_of_chilies.*' => ['required', 'numeric'],
            'number_of_damaged_chilies.*' => ['required', 'numeric'],
            'image_url.*' => ['nullable', 'max:5000']
        ];
    }
}