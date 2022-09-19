<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateStockRequest extends FormRequest
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
            'price' => 'required|numeric|min:1',
            'quantity' => 'required|numeric|min:1',
            'product_id' => 'required|numeric|exists:products,id',
            'photo' => 'nullable',
            'photo.*' => 'nullable|file|mimes:jpg,png',
        ];
    }
}
