<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProductRequest extends FormRequest
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
     * @return array
     */
    public function rules()
    {
        return [
            'product_name' => 'required|string',
            'quantity' => 'required',
            'price' => 'required|numeric|min:0',
            'brand' => 'required|string',
            'discount' => 'required|numeric|min:0',
            'ingredient' => 'required|string',
            'description' => 'required|string',
            'category_id' => 'required|integer|min:0',
            'image_url.*' => 'file|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ];
    }
}
