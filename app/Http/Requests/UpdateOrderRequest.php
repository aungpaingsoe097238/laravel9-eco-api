<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateOrderRequest extends FormRequest
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
            'status_id' => 'nullable|integer|exists:statuses,id',
            'user_id' => 'required|integer|exists:users,id',
            'state_id' => 'required',
            'payment_id' => 'nullable|integer|exists:payments,id',
            'address' => 'nullable',
            'products' => 'nullable',
            'products*' => 'integer|exists:products,id',
        ];
    }
}
