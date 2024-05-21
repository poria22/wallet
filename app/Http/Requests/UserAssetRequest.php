<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserAssetRequest extends FormRequest
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
            'user_id' => ['required', 'exists:users,id'],
            'asset_id' => ['required_if:change_type,increase,decrease', 'exists:assets,id'],
            'amount' => ['required', 'numeric'],
            'change_type' => ['required', 'in:increase,decrease,convert'],
            'from_asset_id' => ['required_if:change_type,convert', 'exists:assets,id'],
            'to_asset_id' => ['required_if:change_type,convert', 'exists:assets,id']
        ];
    }

    public function messages()
    {
        return [
            'user_id.required' => 'user_id field is required',
            'asset_id.required' => 'asset_id field is required',
            'asset_id.exists' => 'asset_id not found',
            'amount.required' => 'amount field is required',
            'amount.numeric' => 'amount field must be a number',
            'change_type.required' => 'change_type field is required',
            'change_type.in' => 'change_type field must be either the word increase or the word decrease',
            'from_asset_id' => 'from_asset_id field is required',
            'to_asset_id' => 'to_asset_id field is required'
        ];
    }
}
