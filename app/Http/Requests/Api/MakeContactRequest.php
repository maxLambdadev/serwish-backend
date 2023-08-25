<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;

/**
 * @OA\Schema()
 */
class MakeContactRequest extends FormRequest
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
     * @OA\Property(format="string", description="phone", property="phone"),
     * @OA\Property(format="string", description="title", property="title"),
     * @OA\Property(format="string", description="description", property="description"),
     * @OA\Property(format="string", description="subject", property="subject"),
     * @OA\Property(format="string", description="email", property="email"),
     */
    public function rules()
    {
        return [
            'phone'  => 'required',
            'title'  => 'required',
            'description'  => 'required',
        ];
    }
}
