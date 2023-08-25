<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateUserRequest extends FormRequest
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
            'email'=>'  required|unique:users,email,'.$id,
            'password'=>'nullable|confirmed'
        ];
    }

}
