<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;

class ParseProjectionsRequest extends Request
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
            
            'pitchers-csv' => 'required',
            'hitters-csv' => 'required'
        ];
    }

    public function messages()
    {
        return [

            'pitchers-csv.required' => 'The pitchers csv field is required.',
            'hitters-csv.required' => 'The hitters csv field is required.'
        ];
    }
}