<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class addVendor extends FormRequest
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
            'item'=>'required|string|unique:items,item',
            'vendor_name'=>'required|string|unique:items,vendor_name',
             'item_price'=>'required|numeric|gt:0|decimal:0,2',
        ];
    }
}
