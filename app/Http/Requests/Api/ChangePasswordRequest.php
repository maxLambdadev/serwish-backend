<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;

/**
 * @OA\Schema(
 * )
 */
class ChangePasswordRequest extends FormRequest
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
     * @OA\Property(type="string", property="password"),
     * @OA\Property(type="string", property="password_confirmation"),
     * @OA\Property(type="integer", property="sms_validation")
     */
    public function rules()
    {
        return [
            'sms_validation'  => 'required|integer|min:4',
            'password' => 'required|confirmed|min:6',
        ];
    }
}
