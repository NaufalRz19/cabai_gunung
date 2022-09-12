<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePurchaseRequest extends FormRequest
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
            'purchase_number' => ['nullable', 'string', 'max:20'],
            'payment_method' => ['nullable', 'in:cod,transfer'],
            'chilli_price_id.*' => ['nullable', 'exists:chilli_prices,id'],
            'healthy_amount_of_chilies.*' => ['nullable', 'numeric'],
            'number_of_damaged_chilies.*' => ['nullable', 'numeric'],
            'image_url.*' => ['nullable', 'max:5000']
        ];
    }
}
