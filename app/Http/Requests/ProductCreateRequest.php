<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProductCreateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'title' => 'required|string|max:255|unique:products,title',
            'slug' => 'nullable|string|max:255|unique:products,slug',
            'price' => 'required|numeric|min:0',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,bmp|max:2048',
        ];
    }

    // Optionally, customize the validation messages
    public function messages(): array
    {
        return [
            'title.required' => 'The title is required.',
            'title.unique' => 'The title must be unique.',
            'slug.unique' => 'The slug must be unique.',
            'price.required' => 'The price is required.',
            'price.numeric' => 'The price must be a number.',
            'image.image' => 'The file must be an image.',
            'image.mimes' => 'The image must be a file of type: jpg, jpeg, png, bmp.',
            'image.max' => 'The image size must not exceed 2048 kilobytes.',
        ];
    }
}
