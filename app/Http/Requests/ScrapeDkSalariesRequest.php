<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;

class ScrapeDkSalariesRequest extends Request
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
            
            'date' => 'required',
            'csv' => 'required'
        ];
    }

    public function messages()
    {
        return [

            'date.required' => 'The date field is required.',
            'csv.required' => 'The csv field is required.'
        ];
    }
}
