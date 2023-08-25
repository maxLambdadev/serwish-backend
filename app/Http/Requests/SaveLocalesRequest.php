<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SaveLocalesRequest extends FormRequest
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
        $id = $this->id == null ? 0 : $this->id;
        return [
            'name'=>'required',
            'original_name'=>'required',
            'iso_code'=>'  required|unique:locales,iso_code,'.$id,
        ];
    }
}
