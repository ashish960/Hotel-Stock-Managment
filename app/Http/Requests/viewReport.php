<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class viewReport extends FormRequest
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
            'item_name'=>'nullable|string|exists:stocks,item_name',
            'date_from' => 'required|date|date_format:Y-m-d|before_or_equal:date_to',
            'date_to' => 'required|date|date_format:Y-m-d|after_or_equal:date_from',
        ];


        $messages = [
            'date_from.date' => 'The start date must be a valid date.',
            'date_from.before_or_equal' => 'The start date must be a date before or equal to the end date.',
            'date_to.date' => 'The end date must be a valid date.',
            'date_to.after_or_equal' => 'The end date must be a date after or equal to the start date.',
        ];
        
    }

    
}
