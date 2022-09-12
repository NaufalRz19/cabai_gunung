<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePurchaseDetailRequest extends FormRequest
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
            'purchase_id' => ['required', 'exists:purchases,id'],
            'chilli_price_id' => ['required', 'exists:chilli_prices,id'],
            'healthy_amount_of_chilies' => ['required', 'integer'],
            'number_of_damaged_chilies' => ['required', 'integer'],
        ];
    }
}
