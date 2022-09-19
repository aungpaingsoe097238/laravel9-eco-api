<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateProductRequest extends FormRequest
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
            'name' => 'required|min:3|unique:products,name|string',
            'description' => 'required|min:3',
            'price' => 'required|numeric|min:1',
            'quantity' => 'required|numeric|min:1',
            'photo' => 'required',
            'photo.*' => 'required|file|mimes:jpg,png',
        ];
    }
}
