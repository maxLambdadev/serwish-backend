<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;

/**
 * @OA\Schema()
 */
class NameSearchRequest extends FormRequest
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
     *
     * @OA\Property(format="locale", default="ka",description="locale", property="locale"),
     * @OA\Property(format="string", default="name",description="name", property="name"),
     */
    public function rules()
    {
        return [
            'locale'  => 'required',
            'name'  => 'required',
        ];
    }
}
