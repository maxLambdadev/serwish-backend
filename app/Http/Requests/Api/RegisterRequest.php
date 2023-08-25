<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;

/**
 * @OA\Schema(
 *     required={"email","gender","password","password_confirmation","gender",
 *     "date_of_birth","phone_number","personal","client_type"}
 * )
 */
class RegisterRequest extends FormRequest
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
     * @OA\Property(type="string", property="email"),
     * @OA\Property(type="string", property="name"),
     * @OA\Property(type="string", property="password"),
     * @OA\Property(type="string", property="password_confirmation"),
     * @OA\Property(type="enum", property="gender",enum={"male", "female", "other"} ),
     * @OA\Property(format="string",type="string", property="date_of_birth", description="Y-m-d"),
     * @OA\Property(type="number", property="phone_number"),
     * @OA\Property(type="enum", property="personal", enum={"personal", "business"} ),
     * @OA\Property(type="enum", property="client_type", enum={"client", "employee"})
     * @OA\Property(type="integer", property="sms_validation")
     */
    public function rules()
    {
        return [
            'sms_validation'  => 'required|integer|min:4',
//            'email'  => 'required|email|unique:users',
            'password' => 'required|confirmed|min:6',
            'gender'    => 'in:male,female,other',
            'date_of_birth'    => 'required|date|date_format:Y-m-d',
            'phone_number'    => 'required',
            'personal'    => 'in:personal,business,',
            'client_type'    => 'in:client,employee',
        ];
    }
}
