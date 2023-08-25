<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;

/**
 * @OA\Schema()
 */
class MakeCallRequest extends FormRequest
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
     * @OA\Property(format="string", description="phone_number", property="phone_number"),
     * @OA\Property(format="integer", description="category_id", property="category_id"),
     */
    public function rules()
    {
        return [
            'category_id'  => 'required|integer',
            'phone_number'  => 'required',
        ];
    }
}
