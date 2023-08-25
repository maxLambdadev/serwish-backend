<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;

/**
 * @OA\Schema()
 */
class SearchRequest extends FormRequest
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
     * @OA\Property(format="string", description="key", property="key"),
     * @OA\Property(type="enum", property="where",enum={"services", "categories", "specialist" ,"post_categories" ,"posts", "cities"} )
     */
    public function rules()
    {
        return [
            'key'  => 'required',
            'where' => 'required|array'
        ];
    }
}
