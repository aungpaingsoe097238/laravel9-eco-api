<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateStockRequest extends FormRequest
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
            'photo' => 'required',
            'photo.*' => 'required|file|mimes:jpg,png',
        ];
    }
}
