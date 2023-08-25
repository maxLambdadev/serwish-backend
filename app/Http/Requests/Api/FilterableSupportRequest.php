<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;

/**
 * @OA\Schema()
 */
class FilterableSupportRequest extends FormRequest
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
     * @OA\Property(format="string", default="POST",description="type", property="type"),
     * @OA\Property(format="integer", default=1,description="page", property="page"),
     * @OA\Property(format="integer", default=20,description="perPage", property="perPage"),
     */
    public function rules()
    {
        return [
            'locale'  => 'nullable',
            'type'  => 'nullable',
            'page'  =>  'nullable|integer',
            'perPage'  =>  'nullable|integer',
        ];
    }
}
