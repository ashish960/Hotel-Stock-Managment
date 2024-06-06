<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class recipeProcess extends FormRequest
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
            'recipe_id'=>'required|integer|gt:0|exists:recipes,id',
            'item_id'=>'required|integer|gt:0|exists:items,id',
            'item_quantity'=>'required|numeric|gt:0|decimal:0,3',
        ];  
    }
}
