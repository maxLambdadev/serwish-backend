<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;

/**
 * @OA\Schema()
 */
class SendSmsRequest extends FormRequest
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
     * @return array
     *
     * @OA\Property(type="number", property="phone_number"),
     */
    public function rules()
    {
        return [
            'phone_number'    => 'required',
        ];
    }
}
