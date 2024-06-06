<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class addOutward extends FormRequest
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
          'item_name'=>'required|string|exists:stocks,item_name',
          'outward_quantity'=>'required|numeric|min:0|decimal:0,3',
        ];
    }
}
