<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AssetRequest extends FormRequest
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
            'name' => 'required|string|unique:assets'
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'the name field is required',
            'name.string' => 'the name field must be a string',
            'name.unique' => 'the name has already been taken'
        ];
    }
}
