<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class MakeDefaultImageRequest extends FormRequest
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
            'other_id'=>'required',
            'resource_id'=>'required',
            'other_entity'=>'required',
            'is_active'=>'required'
        ];
    }
}
