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
            
            'player-pool-id' => 'required',
            'razzball-pitchers-csv' => 'required_without_all:razzball-hitters-csv,bat-csv',
            'razzball-hitters-csv' => 'required_without_all:razzball-pitchers-csv,bat-csv',
            'bat-csv' => 'required_without_all:razzball-pitchers-csv,razzball-hitters-csv'
        ];
    }

    public function messages()
    {
        return [

            'player-pool-id.required' => 'The player pool ID is required.'
        ];
    }
}